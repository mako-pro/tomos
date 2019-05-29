<?php

namespace placer\tomos\controllers\content;

use mako\utility\Str;
use mako\http\routing\Controller;
use placer\tomos\models\Profile;
use placer\tomos\models\Image;

class ImagesController extends Controller
{
    /**
     * Outputs the account settings page
     *
     * @return mixed
     */
    public function page()
    {
        if (! $user = $this->gatekeeper->getUser())
        {
            return $this->redirectResponse('tomos.login.page');
        }

        $id = $user->getId();

        return $this->view->render('tomos::content.images', [
            'profile' => Profile::getByUserId($id)->toArray(),
            'images'  => Image::paginateByUserId($id),
            'user'    => $user->toArray(),
        ]);
    }

    /**
     * Managing uploaded user content
     *
     * @return mixed
     */
    public function addImage()
    {
        if (! $user = $this->gatekeeper->getUser())
        {
           return $this->declineInvalidRequest();
        }

        $post = $this->request->getPost();

        $files = $this->request->getFiles();

        $inputs = $files->all() + $post->all();

        $rules = $this->config->get('tomos::rules.content.images.add');

        $validator = $this->validator->create($inputs, $rules);

        $validator->addRulesIf('original', ['min_dimensions(640, 360)'], function () use($post)
        {
            return $post->get('orient') == 'land';
        });

        $validator->addRulesIf('original', ['min_dimensions(360, 540)'], function () use($post)
        {
            return $post->get('orient') == 'port';
        });

        $validator->addRulesIf('cropped', ['exact_dimensions(373, 210)'], function () use($post)
        {
            return $post->get('orient') == 'land';
        });

        $validator->addRulesIf('cropped', ['exact_dimensions(242, 363)'], function () use($post)
        {
            return $post->get('orient') == 'port';
        });

        if (! $validator->isValid())
        {
            $this->response->setStatus('400');

            return $this->jsonResponse([
                'messages' => $validator->getErrors()
            ]);
        }

        $uploadsPath   = $this->tomos->uploadsPath;
        $imagePath     = $this->tomos->generateFilePath();
        $directoryPath = $uploadsPath . $imagePath;

        if (! $this->fileSystem->isDirectory($directoryPath))
        {
            $this->fileSystem->createDirectory($directoryPath, '0777', true);
        }

        $original = $files->get('original');
        $fileSize = $original->getReportedSize();
        $fileType = $original->getReportedType();
        $fileExt  = str_replace('image/', '', $fileType);
        $fileName = uniqid(true);
        $suffix   = "/{$fileName}.{$fileExt}";
        $path = $directoryPath . $suffix;
        $original->moveTo($path);

        $cropped = $files->get('cropped');
        $suffix  = "/__{$fileName}.jpg";
        $path = $directoryPath . $suffix;
        $cropped->moveTo($path);

        $image = new Image;

        $image->user_id   = $user->getId();
        $image->path      = $imagePath;
        $image->title     = $post->get('title');
        $image->text      = Str::nl2br($post->get('text'));
        $image->file_name = $fileName;
        $image->file_type = $fileType;
        $image->file_ext  = $fileExt;
        $image->file_size = $fileSize;
        $image->orient    = $post->get('orient');
        $image->enabled   = 1;
        $image->save();

        $responseData = [
            'url' => $this->urlBuilder->to("/tomos/content/images"),
        ];

        return $this->jsonResponse(['success' => $responseData]);
    }

    /**
     * Edit image
     *
     * @return mixed
     */
    public function editImage(int $id)
    {
        if (! $user = $this->gatekeeper->getUser())
        {
           return $this->declineInvalidRequest();
        }

        if (! $image = Image::getFirstByUserId($id, $user->getId()))
        {
            return $this->declineInvalidRequest();
        }

        $post = $this->request->getPost();

        $formType = $post->get('form_type');

        if ($formType == 'edit-titles')
        {
            $rules = $this->config->get('tomos::rules.content.images.edit');

            $validator = $this->validator->create($post->all(), $rules);

            if (! $validator->isValid())
            {
                $this->response->setStatus('400');

                return $this->jsonResponse([
                    'messages' => $validator->getErrors()
                ]);
            }
        }

        switch ($formType)
        {
            case 'edit-titles':
                $image->title = $post->get('title');
                $image->text  = Str::nl2br($post->get('text'));
                $image->save();
                $success = ['title' => $image->title, 'text' => $image->text];
                break;
            case 'enabled':
                $image->enabled = $post->get('enabled');
                $image->save();
                $success = $image->enabled == 1 ? 'Enabled' : 'Disabled';
                break;
            case 'delete':
                $success = $image->id;
                $directory = $image->path;
                $uploadsPath = $this->tomos->uploadsPath;
                $directoryPath = $uploadsPath . $directory;
                $imagePath = $directoryPath . '/' . $image->file_name . '.' . $image->file_ext;
                if ($this->fileSystem->isFile($imagePath))
                    $this->fileSystem->removeDirectory(dirname($imagePath));
                $image->delete();
                break;
            default:
                $success = '10001';
                break;
        }

        return $this->jsonResponse(['success' => $success]);
    }

    /**
     * Declines the bad requests
     *
     * @return Response
     */
    protected function declineInvalidRequest()
    {
        $this->response->setStatus('403');
        $this->response->setBody('Forbidden 403!');
        return $this->response->send();
    }

}
