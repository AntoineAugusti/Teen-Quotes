<?php

class StoriesAPIv1Controller extends BaseController {
	
	public function index()
	{
		$page = Input::get('page', 1);
		$pagesize = Input::get('pagesize', Config::get('app.stories.nbStoriesPerPage'));

        if ($page <= 0)
			$page = 1;

		// Number of stories to skip
        $skip = $pagesize * ($page - 1);

		$totalStories = Story::count();

        // Get stories
        $content = Story::
	        with(array('user' => function($q)
			{
			    $q->addSelect(array('id', 'login', 'avatar'));
			}))
			->orderDescending()
			->take($pagesize)
			->skip($skip)
			->get();

		// Handle no stories found
		if (is_null($content) OR $content->count() == 0) {
			$data = [
				'status' => 404,
				'error' => 'No stories have been found.'
			];

			return Response::json($data, 404);
		}

		$data = APIGlobalController::paginateContent($page, $pagesize, $totalStories, $content, 'stories');
		
		return Response::json($data, 200, [], JSON_NUMERIC_CHECK);
	}

	public function show($story_id)
	{
		$story = Story::where('id', '=', $story_id)
			->with(array('user' => function($q)
			{
			    $q->addSelect(array('id', 'login', 'avatar'));
			}))
			->first();

		// Handle not found
		if (is_null($story)) {

			$data = [
				'status' => 'story_not_found',
				'error'  => "The story #".$story_id." was not found",
			];

			return Response::json($data, 404);
		}
		else
			return Response::json($story, 200, [], JSON_NUMERIC_CHECK);
	}
}