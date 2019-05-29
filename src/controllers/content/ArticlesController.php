<?php

namespace placer\tomos\controllers\content;

use mako\utility\Str;
use mako\http\routing\Controller;
use placer\tomos\models\Profile;
use placer\tomos\models\Article;

class ArticlesController extends Controller
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

        return $this->view->render('tomos::content.articles', [
            'profile'  => Profile::getByUserId($id)->toArray(),
            'articles' => Article::paginateByUserId($id),
            'user'     => $user->toArray(),
        ]);
    }

    /**
     * Add new article
     *
     * @return mixed
     */
    public function addArticle()
    {
        if (! $user = $this->gatekeeper->getUser())
        {
           return $this->declineInvalidRequest();
        }

        $post   = $this->request->getPost();
        $files  = $this->request->getFiles();
        $inputs = $files->all() + $post->all();

        $rules = $this->config->get('tomos::rules.content.articles.add');

        $validator = $this->validator->create($inputs, $rules);

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
        $path     = $directoryPath . $suffix;
        $original->moveTo($path);

        $cover  = $files->get('cover');
        $suffix = "/__{$fileName}.jpg";
        $path   = $directoryPath . $suffix;
        $cover->moveTo($path);

        $article = new Article;

        $article->user_id = $user->getId();
        $article->slug    = $post->get('slug');
        $article->page    = 1;
        $article->cover   = "{$imagePath}/__{$fileName}.jpg";
        $article->title   = $post->get('title');
        $article->body    = Str::nl2br($post->get('body'));
        $article->enabled = 1;
        $article->save();

        $responseData = [
            'url' => $this->urlBuilder->to("/tomos/content/articles"),
        ];

        return $this->jsonResponse(['success' => $responseData]);
    }

    /**
     * Edit Article
     *
     * @return mixed
     */
    public function editArticle(int $id)
    {
        if (! $user = $this->gatekeeper->getUser())
        {
           return $this->declineInvalidRequest();
        }

        if (! $article = Article::getFirstByUserId($id, $user->getId()))
        {
            return $this->declineInvalidRequest();
        }

        $post = $this->request->getPost();

        $formType = $post->get('form_type');

        if ($formType != 'enabled' && $formType != 'delete')
        {
            $rules = $this->config->get("tomos::rules.content.articles.{$formType}");

            if ($formType == 'edit-cover')
            {
                $post = $this->request->getFiles();
            }

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
            case 'edit-cover':
                $uploadsPath = $this->tomos->uploadsPath;
                $oldFilePath = $uploadsPath . $article->cover;
                if ($this->fileSystem->isFile($oldFilePath))
                    $this->fileSystem->removeDirectory(dirname($oldFilePath));
                $imagePath = $this->tomos->generateFilePath();
                $directoryPath = $uploadsPath . $imagePath;
                if (! $this->fileSystem->isDirectory($directoryPath))
                    $this->fileSystem->createDirectory($directoryPath, '0777', true);
                $original = $post->get('original');
                $fileSize = $original->getReportedSize();
                $fileType = $original->getReportedType();
                $fileExt  = str_replace('image/', '', $fileType);
                $fileName = uniqid(true);
                $suffix   = "/{$fileName}.{$fileExt}";
                $path     = $directoryPath . $suffix;
                $original->moveTo($path);
                $cover  = $post->get('cover');
                $suffix = "/__{$fileName}.jpg";
                $path   = $directoryPath . $suffix;
                $cover->moveTo($path);
                $article->cover = "{$imagePath}/__{$fileName}.jpg";
                $article->save();
                $success = $article->id;
                break;
            case 'edit-text':
                $article->title = $post->get('title');
                $article->slug  = $post->get('slug');
                $article->body  = Str::nl2br($post->get('body'));
                $article->save();
                $success = ['title' => $article->title, 'slug' => $article->slug, 'body' => $article->body];
                break;
            case 'enabled':
                $article->enabled = $post->get('enabled');
                $article->save();
                $success = $article->enabled == 1 ? 'Enabled' : 'Disabled';
                break;
            case 'delete':
                $success = $article->id;
                $uploadsPath = $this->tomos->uploadsPath;
                $filePath = $uploadsPath . $article->cover;
                if ($this->fileSystem->isFile($filePath))
                    $this->fileSystem->removeDirectory(dirname($filePath));
                $article->delete();
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
