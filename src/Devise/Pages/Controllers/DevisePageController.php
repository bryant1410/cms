<?php

use Devise\Pages\Repositories\PagesRepository;
use Devise\Pages\Response\ResponseBuilder;

class DevisePageController extends Controller {
    protected $PagesRepository;
    protected $ResponseBuilder;

    /**
     * Creates a new DvsPagesController instance.
     *
     * @param  PagesRepository $PagesRepository
     * @param  ResponseBuilder $ResponseBuilder
     * @return \DevisePageController
     */
    public function __construct(PagesRepository $PagesRepository, ResponseBuilder $ResponseBuilder)
    {
        $this->PagesRepository = $PagesRepository;
        $this->ResponseBuilder = $ResponseBuilder;
    }

	/**
	 * Displays details of a page
	 *
	 * @internal param array $slug
	 * @return Response
	 */
    public function show()
    {
        // what does it mean to be in editing mode? right now it is just when you are logged in
        $editing = !is_null(Auth::user()); //&& Input::get('editing', false);

        $page = $this->PagesRepository->findByRouteName( Route::currentRouteName(), Input::get('page_version', 'Default'), $editing);

        $localized = $this->PagesRepository->findLocalizedPage($page);

        return $localized ? Redirect::route($localized->route_name) : $this->ResponseBuilder->retrieve($page);
    }
}