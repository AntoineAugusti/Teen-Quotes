<?php

/*
 * This file is part of the Teen Quotes website.
 *
 * (c) Antoine Augusti <antoine.augusti@teen-quotes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TeenQuotes\Tags\Repositories;

use Cache;
use TeenQuotes\Quotes\Models\Quote;
use TeenQuotes\Tags\Models\Tag;

class CachingTagRepository implements TagRepository
{
    /**
     * @var \TeenQuotes\Tags\Repositories\TagRepository
     */
    private $tags;

    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;
    }

    /**
     * Create a new tag.
     *
     * @param string $name
     *
     * @return \TeenQuotes\Tags\Models\Tag
     */
    public function create($name)
    {
        return $this->tags->create($name);
    }

    /**
     * Get a tag thanks to its name.
     *
     * @param string $name
     *
     * @return \TeenQuotes\Tags\Models\Tag|null
     */
    public function getByName($name)
    {
        $callback = function () use ($name) {
            return $this->tags->getByName($name);
        };

        return Cache::rememberForever('tags.name-'.$name, $callback);
    }

    /**
     * Add a tag to a quote.
     *
     * @param \TeenQuotes\Quotes\Models\Quote $q
     * @param \TeenQuotes\Tags\Models\Tag     $t
     */
    public function tagQuote(Quote $q, Tag $t)
    {
        Cache::forget($this->cacheNameForListTags($q));

        $keyTotal = $this->cacheNameTotalQuotesForTag($t);

        if (Cache::has($keyTotal)) {
            Cache::increment($keyTotal);
        }

        return $this->tags->tagQuote($q, $t);
    }

    /**
     * Remove a tag from a quote.
     *
     * @param \TeenQuotes\Quotes\Models\Quote $q
     * @param \TeenQuotes\Tags\Models\Tag     $t
     */
    public function untagQuote(Quote $q, Tag $t)
    {
        Cache::forget($this->cacheNameForListTags($q));

        $keyTotal = $this->cacheNameTotalQuotesForTag($t);

        if (Cache::has($keyTotal)) {
            Cache::decrement($keyTotal);
        }

        return $this->tags->untagQuote($q, $t);
    }

    /**
     * Get a list of tags for a given quote.
     *
     * @param \TeenQuotes\Quotes\Models\Quote $q
     *
     * @return array
     */
    public function tagsForQuote(Quote $q)
    {
        $key = $this->cacheNameForListTags($q);

        $callback = function () use ($q) {
            return $this->tags->tagsForQuote($q);
        };

        return Cache::remember($key, 10, $callback);
    }

    /**
     * Get the total number of quotes having a tag.
     *
     * @param \TeenQuotes\Tags\Models\Tag $t
     *
     * @return int
     */
    public function totalQuotesForTag(Tag $t)
    {
        $key = $this->cacheNameTotalQuotesForTag($t);

        $callback = function () use ($t) {
            return $this->tags->totalQuotesForTag($t);
        };

        return Cache::remember($key, 10, $callback);
    }

    /**
     * Get the quotes that are not tagged yet but should be tagged.
     *
     * @param \TeenQuotes\Tags\Models\Tag $t
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function quotesToTag(Tag $t)
    {
        return $this->tags->quotesToTag($t);
    }

    /**
     * Get all tags.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allTags()
    {
        return $this->tags->allTags();
    }

    /**
     * Find related quotes.
     *
     * @param \TeenQuotes\Quotes\Models\Quote $q
     * @param int                             $nb
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function relatedQuotes(Quote $q, $nb=3)
    {
        $key = $this->cacheNameForRelatedQuotes($q, $nb);

        $callback = function () use ($q, $nb) {
            return $this->tags->relatedQuotes($q, $nb);
        };

        return Cache::remember($key, 90, $callback);
    }

    /**
     * Get the key name when we list tags for a quote.
     *
     * @param \TeenQuotes\Quotes\Models\Quote $q
     *
     * @return string
     */
    private function cacheNameForListTags(Quote $q)
    {
        return 'tags.quote-'.$q->id.'.list-name';
    }

    /**
     * Get the key name for related quotes.
     *
     * @param \TeenQuotes\Quotes\Models\Quote $q
     * @param int                             $nb
     *
     * @return string
     */
    private function cacheNameForRelatedQuotes(Quote $q, $nb)
    {
        return 'tags.quote-'.$q->id.'.related.'.$nb;
    }

    /**
     * Get the key name to have the number of quotes
     * having a tag.
     *
     * @param \TeenQuotes\Tags\Models\Tag $t
     *
     * @return string
     */
    private function cacheNameTotalQuotesForTag(Tag $t)
    {
        return 'tags.tag-'.$t->name.'.total-quotes';
    }
}
