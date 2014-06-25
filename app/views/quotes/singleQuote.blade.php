<?php
$openedLink = false;
$colorQuote = $colors[$quote->id];
$darkColorQuote = Quote::adjustBrightness($colors[$quote->id], -30);
if ($i % 2 == 1)
	$transition = 'fadeInRight';
else
	$transition = 'fadeInLeft';
?>
<div class="quote animated <?= $transition; ?>" style="background-color:<?= $colorQuote; ?>;border-bottom-color:<?= $darkColorQuote; ?>">
	{{{ $quote->content }}}

	<div class="row quotes-info">
		<!-- COMMENTS -->
		<div class="col-md-3 col-sm-3 col-xs-2">
			<a class="hidden-sm hidden-xs badge" href="{{ URL::action('QuotesController@show', ['id' => $quote->id]) }}" style="background:<?= $darkColorQuote; ?>">#{{{ $quote->id }}}</a>

			<!-- Has comments -->
			@if ($quote->has_comments)
				<a href="{{ URL::action('QuotesController@show', ['id' => $quote->id]) }}" class="badge hidden-xs hidden-sm" style="background:<?= $darkColorQuote ?>">{{{ Lang::choice('quotes.commentComments', $quote->total_comments, array('nb' => $quote->total_comments)) }}}</a>

				<a href="{{ URL::action('QuotesController@show', ['id' => $quote->id]) }}" class="badge hidden-md hidden-lg" style="background:<?= $darkColorQuote ?>"><i class="fa fa-comment"></i> {{{ $quote->total_comments }}}</a>

			<!-- No comments -->
			@else
				<a href="{{ URL::action('QuotesController@show', ['id' => $quote->id]) }}" class="badge hidden-md hidden-lg" style="background:<?= $darkColorQuote ?>"><i class="fa fa-comment"></i> {{{ $quote->total_comments }}}</a>
			@endif
		</div>

		<!-- FAVORITE -->
		<div class="col-md-3 col-sm-2 col-xs-2 favorite-links">
			@if (Auth::check())
				@if ($quote->isFavoriteForCurrentUser())
					<button data-url="{{URL::route('unfavorite', array($quote->id), true)}}" data-id="{{ $quote->id }}" data-type="unfavorite" class="badge favorite-action" style="background:<?= $darkColorQuote; ?>"><i class="fa fa-heart-o"></i></button>
				@else
					<button data-url="{{URL::route('favorite', array($quote->id), true)}}" data-id="{{ $quote->id }}" data-type="favorite" class="badge favorite-action" style="background:<?= $darkColorQuote; ?>"><i class="fa fa-heart"></i></button>
				@endif
			@endif
		</div>

		<!-- AUTHOR -->
		<div class="col-md-5 col-sm-5 col-xs-5">
			@if (!(isset($hideAuthorQuote) AND $hideAuthorQuote))
				@if (!$quote->user->isHiddenProfile())
					<a href="{{ URL::action('UsersController@show', ['id' => $quote->user->login]) }}" class="link-author-profile">
					<?php $openedLink = true; ?>
				@else
					<span class="link-author-profile">
				@endif

				{{{ $quote->user->login }}}

				@if ($openedLink)
					</a>
				@else
					</span>
				@endif
			@endif
		</div>

		<!-- SOCIAL BUTTONS -->
		<div class="col-md-1 col-sm-2 col-xs-3 social-buttons">
			<a href="https://www.facebook.com/sharer.php?u={{URL::route('quotes.show', array($quote->id), true)}}" style="background:<?= $darkColorQuote ?>" target="_blank"><i class="fa fa-facebook"></i></a>
			<a href="https://twitter.com/home?status={{{ $quote->textTweet() }}}" style="background:<?= $darkColorQuote ?>" target="_blank"><i class="fa fa-twitter"></i></a>
		</div>
	</div>
</div>