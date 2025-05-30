<?php
/*
* SiteSEO
* https://siteseo.io/
* (c) SiteSEO Team <support@siteseo.io>
*/

/*
Copyright 2016 - 2024 - Benjamin Denis  (email : contact@seopress.org)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace SiteSEO\Actions\Admin\Importer;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

use SiteSEO\Core\Hooks\ExecuteHooksBackend;
use SiteSEO\Thirds\RankMath\Tags;

class RankMath implements ExecuteHooksBackend {
	
	public $tagsRankMath;
	
	public function __construct() {
		$this->tagsRankMath = new Tags();
	}

	/**
	 * @since 4.3.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action('wp_ajax_siteseo_rk_migration', [$this, 'process']);
	}

	/**
	 * @since 4.3.0
	 *
	 * @return string
	 */
	protected function migrateTermQuery() {
		wp_reset_query();

		$args = [
			'hide_empty' => false,
			'fields'	 => 'ids',
		];
		$rk_query_terms = get_terms($args);

		$getTermMetas = [
			'_siteseo_titles_title'			 => 'rank_math_title',
			'_siteseo_titles_desc'			  => 'rank_math_description',
			'_siteseo_social_fb_title'		  => 'rank_math_facebook_title',
			'_siteseo_social_fb_desc'		   => 'rank_math_facebook_description',
			'_siteseo_social_fb_img'			=> 'rank_math_facebook_image',
			'_siteseo_social_twitter_title'	 => 'rank_math_twitter_title',
			'_siteseo_social_twitter_desc'	  => 'rank_math_twitter_description',
			'_siteseo_social_twitter_img'	   => 'rank_math_twitter_image',
			'_siteseo_robots_canonical'		 => 'rank_math_canonical_url',
			'_siteseo_analysis_target_kw'	   => 'rank_math_focus_keyword',
		];
		if ( ! $rk_query_terms) {
			wp_reset_query();

			return 'done';
		}

		foreach ($rk_query_terms as $term_id) {
			foreach ($getTermMetas as $key => $value) {
				$metaRankMath = get_term_meta($term_id, $value, true);
				if ( ! empty($metaRankMath)) {
					update_term_meta($term_id, $key, $this->tagsRankMath->replaceTags($metaRankMath));
				}
			}

			if ('' != get_term_meta($term_id, 'rank_math_robots', true)) { //Import Robots NoIndex, NoFollow, NoImageIndex, NoArchive, NoSnippet
				$rank_math_robots = get_term_meta($term_id, 'rank_math_robots', true);

				if (in_array('noindex', $rank_math_robots)) {
					update_term_meta($term_id, '_siteseo_robots_index', 'yes');
				}
				if (in_array('nofollow', $rank_math_robots)) {
					update_term_meta($term_id, '_siteseo_robots_follow', 'yes');
				}
				if (in_array('noimageindex', $rank_math_robots)) {
					update_term_meta($term_id, '_siteseo_robots_imageindex', 'yes');
				}
				if (in_array('noarchive', $rank_math_robots)) {
					update_term_meta($term_id, '_siteseo_robots_archive', 'yes');
				}
				if (in_array('nosnippet', $rank_math_robots)) {
					update_term_meta($term_id, '_siteseo_robots_snippet', 'yes');
				}
			}
		}

		wp_reset_query();

		return 'done';
	}

	/**
	 * @since 4.3.0
	 *
	 * @param int $offset
	 * @param int $increment
	 */
	protected function migratePostQuery($offset, $increment) {
		$args = [
			'posts_per_page' => $increment,
			'post_type'	  => 'any',
			'post_status'	=> 'any',
			'offset'		 => $offset,
		];

		$rk_query = get_posts($args);

		if ( ! $rk_query) {
			$offset += $increment;

			return $offset;
		}

		$getPostMetas = [
			'_siteseo_titles_title'		 => 'rank_math_title',
			'_siteseo_titles_desc'		  => 'rank_math_description',
			'_siteseo_social_fb_title'	  => 'rank_math_facebook_title',
			'_siteseo_social_fb_desc'	   => 'rank_math_facebook_description',
			'_siteseo_social_fb_img'		=> 'rank_math_facebook_image',
			'_siteseo_social_twitter_title' => 'rank_math_twitter_title',
			'_siteseo_social_twitter_desc'  => 'rank_math_twitter_description',
			'_siteseo_social_twitter_img'   => 'rank_math_twitter_image',
			'_siteseo_robots_canonical'	 => 'rank_math_canonical_url',
			'_siteseo_analysis_target_kw'   => 'rank_math_focus_keyword',
		];

		foreach ($rk_query as $post) {
			foreach ($getPostMetas as $key => $value) {
				$metaRankMath = get_post_meta($post->ID, $value, true);
				if ( ! empty($metaRankMath)) {
					update_post_meta($post->ID, $key, $this->tagsRankMath->replaceTags($metaRankMath));
				}
			}

			if ('' != get_post_meta($post->ID, 'rank_math_robots', true)) { //Import Robots NoIndex, NoFollow, NoImageIndex, NoArchive, NoSnippet
				$rank_math_robots = get_post_meta($post->ID, 'rank_math_robots', true);

				if (in_array('noindex', $rank_math_robots)) {
					update_post_meta($post->ID, '_siteseo_robots_index', 'yes');
				}
				if (in_array('nofollow', $rank_math_robots)) {
					update_post_meta($post->ID, '_siteseo_robots_follow', 'yes');
				}
				if (in_array('noimageindex', $rank_math_robots)) {
					update_post_meta($post->ID, '_siteseo_robots_imageindex', 'yes');
				}
				if (in_array('noarchive', $rank_math_robots)) {
					update_post_meta($post->ID, '_siteseo_robots_archive', 'yes');
				}
				if (in_array('nosnippet', $rank_math_robots)) {
					update_post_meta($post->ID, '_siteseo_robots_snippet', 'yes');
				}
			}
		}

		$offset += $increment;

		return $offset;
	}

	/**
	 * @since 4.3.0
	 */
	public function process() {
		siteseo_check_ajax_referer('siteseo_rk_migrate_nonce');
		if ( ! is_admin()) {
			wp_send_json_error();

			return;
		}

		if ( ! current_user_can(siteseo_capability('manage_options', 'migration'))) {
			wp_send_json_error();

			return;
		}

		if (isset($_POST['offset'])) {
			$offset = absint(siteseo_opt_post('offset'));
		}

		global $wpdb;
		$total_count_posts = (int) $wpdb->get_var("SELECT count(*) FROM {$wpdb->posts}");

		$increment = 200;
		global $post;

		if ($offset > $total_count_posts) {
			$offset = $this->migrateTermQuery();
		} else {
			$offset = $this->migratePostQuery($offset, $increment);
		}

		$data = [];
		$data['offset'] = $offset;

		do_action('siteseo_third_importer_rank_math', $offset, $increment);

		wp_send_json_success($data);
		exit();
	}
}
