<?php


class HELPER_FAKE {

	public static function wpml_translate( $taxonomy_info, $string = '', $index = -1 ): string {
		if ( empty( $string ) ) {
			if ( is_object( $taxonomy_info ) ) {

				$string = $taxonomy_info->label;

			}
		}

		return $string;
	}
}
