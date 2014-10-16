<?php namespace Devise\Pages\Response;

use Devise\Pages\PageManager, Devise\Fields\FieldManager, Devise\Collections\CollectionInstanceManager;
use Devise\Pages\PageVersionManager;
use Devise\Pages\Repositories\PagesRepository;
use Illuminate\Routing\Redirector;

class ResponseHandler
{
    private $PageManager, $PagesRepository, $FieldManager, $Redirect, $CollectionInstanceManager, $PageVersionManager;

    public function __construct(PageManager $PageManager, PagesRepository $PagesRepository, FieldManager $FieldManager, Redirector $Redirect, CollectionInstanceManager $CollectionInstanceManager, PageVersionManager $PageVersionManager)
    {
        $this->PageManager = $PageManager;
        $this->PagesRepository = $PagesRepository;
        $this->FieldManager = $FieldManager;
        $this->Redirect = $Redirect;
        $this->CollectionInstanceManager = $CollectionInstanceManager;
        $this->PageVersionManager = $PageVersionManager;
    }

    public function requestCreateNewPage($input) {

        $page = $this->PageManager->createNewPage($input);

        if ($page) {
            return $this->Redirect->route('dvs-pages');
        } else {
            return $this->Redirect->route('dvs-pages-create')
                ->withInput()
                ->withErrors($this->PageManager->errors)
                ->with('message', $this->PageManager->message);
        }
    }

    public function requestUpdatePage($id, $input) {

        $page = $this->PageManager->updatePage($id, $input);

        if ($page) {
            return $this->Redirect->route('dvs-pages');
        } else {
            return $this->Redirect->route('dvs-pages-edit', $id)
                ->withInput()
                ->withErrors($this->PageManager->errors)
                ->with('message', $this->PageManager->message);
        }
    }

	public function requestDestroyPage($id) {

		$page = $this->PageManager->destroyPage($id);

		if ($page) {
			return $this->Redirect->route('dvs-pages')
								  ->with('message', 'Page successfully removed');
		} else {
			return $this->Redirect->route('dvs-pages')
			                      ->withInput()
			                      ->withErrors($this->PageManager->errors)
			                      ->with('message', $this->PageManager->message);
		}

	}

	public function requestCopyPage($id, $input) {

		$page = $this->PageManager->copyPage($id, $input);

		if ($page)
        {
			return $this->Redirect->route('dvs-pages');
		}

		return $this->Redirect->route('dvs-pages-copy', $id)
            ->withInput()
            ->withErrors($this->PageManager->errors)
            ->with('message', $this->PageManager->message);
	}

    public function requestStorePageVersion($input)
    {
        return $this->PageVersionManager->copyPageVersion($input['page_version_id'], $input['name']);
    }

    public function requestPageList($includeAdmin = false)
    {
        if ($includeAdmin != false) {
            return $this->PagesRepository->getPagesList(true, $_GET['term']);
        }

        return $this->PagesRepository->getPagesList(false, $_GET['term']);
    }

    public function requestUpdatePageVersionDates($pageVersionId, $input)
    {
        $this->PageManager->updatePageVersionDates($pageVersionId, $input);

        return '';
    }
}