/**
 * WordPress dependencies.
 */
import { PluginPrePublishPanel } from '@wordpress/editor';
import { useSelect, useDispatch } from '@wordpress/data';
import { useEffect, useMemo } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import { CheckboxControl, PanelRow } from '@wordpress/components';
import { registerPlugin } from '@wordpress/plugins';

const LOCK_KEY = 'site-functionality.prepublish-checks';
const ENFORCE = true; // true = lock saving, false = notices only

const REQUIRED_BLOCK = 'site-functionality/cta-slot';
const MIN_FEATURED_WIDTH = 1200;
const MIN_FEATURED_HEIGHT = 675;

const NOTICE_ID_REQUIRED = 'site-functionality.prepublish-checks.required';
const NOTICE_ID_SUGGESTED = 'site-functionality.prepublish-checks.suggested';

const SUPPORTED_POST_TYPES = [ 'post' ];

		icon: 'no-alt',
		icon: 'no-alt',
/**
 * Determine whether a post type is supported.
 *
 * @param {string} postType Post type.
 * @return {boolean} True if supported.
 */
function isSupportedPostType( postType ) {
	return SUPPORTED_POST_TYPES.indexOf( postType ) !== -1;
}

/**
 * Recursively check whether a block (or any inner block) matches a block name.
 *
 * @param {Array}  blocks    Block array.
 * @param {string} blockName Block name to find.
 * @return {boolean} True if found.
 */
function hasBlock( blocks, blockName ) {
	if ( ! Array.isArray( blocks ) || ! blockName ) {
		return false;
	}

	return blocks.some( function ( block ) {
		if ( block && block.name === blockName ) {
			return true;
		}

		if ( block && Array.isArray( block.innerBlocks ) && block.innerBlocks.length ) {
			return hasBlock( block.innerBlocks, blockName );
		}

		return false;
	} );
}

/**
 * Check if a featured image exists.
 *
 * @param {number} featuredImageId Media ID.
 * @return {boolean} True if present.
 */
function hasImage( featuredImageId ) {
	return Number( featuredImageId ) > 0;
}

/**
 * Get image dimensions from a media object.
 *
 * @param {Object|null} media Media object from core store.
 * @return {{width:number|null,height:number|null}} Dimensions.
 */
function getMediaDimensions( media ) {
	if (
		! media ||
		! media.media_details ||
		! media.media_details.width ||
		! media.media_details.height
	) {
		return { width: null, height: null };
	}

	return {
		width: media.media_details.width,
		height: media.media_details.height,
	};
}

/**
 * Check if media meets minimum dimensions.
 *
 * Note: If dimensions are unavailable, this returns true (cannot validate).
 *
 * @param {Object|null} media     Media object.
 * @param {number}      minWidth  Minimum width.
 * @param {number}      minHeight Minimum height.
 * @return {boolean} True if meets or cannot validate.
 */
function hasMinDimensions( media, minWidth, minHeight ) {
	const dims = getMediaDimensions( media );

	if ( ! dims.width || ! dims.height ) {
		return true;
	}

	return dims.width >= Number( minWidth ) && dims.height >= Number( minHeight );
}

/**
 * Check if any term exists for a taxonomy field (category, tag, etc).
 *
 * @param {Array} terms Term IDs.
 * @return {boolean} True if at least one term.
 */
function hasTerm( terms ) {
	return Array.isArray( terms ) && terms.length > 0;
}

/**
 * Alias: Check for at least one category term.
 *
 * @param {Array} categories Category term IDs.
 * @return {boolean} True if at least one category.
 */
function hasCategory( categories ) {
	return hasTerm( categories );
}

/**
 * Alias: Check for at least one tag term.
 *
 * @param {Array} tags Tag term IDs.
 * @return {boolean} True if at least one tag.
 */
function hasTag( tags ) {
	return hasTerm( tags );
}

/**
 * Check whether a condition type is required.
 *
 * @param {string} conditionType Condition type.
 * @return {boolean} True if required.
 */
function isRequired( conditionType ) {
	return conditionType === 'required';
}

/**
 * Check whether a condition type is suggested.
 *
 * @param {string} conditionType Condition type.
 * @return {boolean} True if suggested.
 */
function isSuggested( conditionType ) {
	return conditionType === 'suggested';
}

/**
 * Condition configuration.
 */
const CONDITIONS = {
	block: {
		condition: 'required',
		label: __( 'CTA Slot block present', 'site-functionality' ),
		messages: {
			complete: __( 'CTA Slot block is present.', 'site-functionality' ),
			incomplete: __( 'CTA Slot block is required.', 'site-functionality' ),
			error: __( 'Unable to verify whether the CTA Slot block is present.', 'site-functionality' ),
		},
	},
	image: {
		condition: 'required',
		label: __( 'Featured Image set', 'site-functionality' ),
		messages: {
			complete: __( 'Featured Image is set.', 'site-functionality' ),
			incomplete: __( 'A Featured Image is required.', 'site-functionality' ),
			error: __( 'Unable to verify whether a Featured Image is set.', 'site-functionality' ),
		},
	},
	min_dimensions: {
		condition: 'required',
		label: __( 'Featured Image meets minimum size', 'site-functionality' ),
		messages: {
			complete: __( 'Featured Image meets the minimum size.', 'site-functionality' ),
			incomplete: sprintf(
				/* translators: 1: min width, 2: min height */
				__( 'Featured Image must be at least %1$d × %2$d.', 'site-functionality' ),
				MIN_FEATURED_WIDTH,
				MIN_FEATURED_HEIGHT
			),
			error: __( 'Unable to verify Featured Image dimensions.', 'site-functionality' ),
		},
	},
	category: {
		condition: 'required',
		label: __( 'At least one Category is selected', 'site-functionality' ),
		messages: {
			complete: __( 'At least one Category is selected.', 'site-functionality' ),
			incomplete: __( 'At least one Category is required.', 'site-functionality' ),
			error: __( 'Unable to verify Categories.', 'site-functionality' ),
		},
	},
	tag: {
		condition: 'suggested',
		label: __( 'Tags have been added', 'site-functionality' ),
		messages: {
			complete: __( 'Tags have been added.', 'site-functionality' ),
			incomplete: __( 'Consider adding Tags to improve discoverability.', 'site-functionality' ),
			error: __( 'Unable to verify Tags.', 'site-functionality' ),
		},
	},
};

/**
 * Evaluate all conditions.
 *
 * @param {Object} state Editor state.
 * @return {Array} List of evaluated condition results.
 */
function evaluateConditions( state ) {
	return [
		{
			key: 'block',
			complete: hasBlock( state.blocks, REQUIRED_BLOCK ),
		},
		{
			key: 'image',
			complete: hasImage( state.featuredImageId ),
		},
		{
			key: 'min_dimensions',
			complete:
				hasImage( state.featuredImageId ) &&
				hasMinDimensions( state.featuredMedia, MIN_FEATURED_WIDTH, MIN_FEATURED_HEIGHT ),
		},
		{
			key: 'category',
			complete: hasCategory( state.categories ),
		},
		{
			key: 'tag',
			complete: hasTag( state.tags ),
		},
	].map( function ( result ) {
		const config = CONDITIONS[ result.key ];

		return {
			key: result.key,
			type: config.condition,
			label: config.label,
			icon: config.icon,
			complete: Boolean( result.complete ),
			messages: config.messages,
		};
	} );
}

/**
 * Build a single-line notice message for a set of failures.
 *
 * @param {Array} failures Failed condition results.
 * @param {string} heading Notice heading.
 * @return {string} Message.
 */
function buildNoticeMessage( failures, heading ) {
	const items = failures
		.map( function ( item ) {
			const icon = item.icon || 'warning';

			return (
				'<li>' +
					'<span class="dashicons dashicons-' + icon + '" style="margin-right:6px;vertical-align:middle;"></span>' +
					item.messages.incomplete +
				'</li>'
			);
		} )
		.join( '' );

	return (
		'<strong>' + heading + '</strong>' +
		'<ul style="margin:8px 0 0 0">' + items + '</ul>'
	);
}

const PrePublishChecklist = () => {
	/**
	 * Select postType first so we can bail early and avoid unnecessary selectors.
	 */
	const postType = useSelect(
		function ( select ) {
			return select( 'core/editor' ).getCurrentPostType();
		},
		[]
	);

	const supported = isSupportedPostType( postType );

	const { lockPostSaving, unlockPostSaving } = useDispatch( 'core/editor' );
	const { createNotice, removeNotice } = useDispatch( 'core/notices' );

	/**
	 * If the post type is not supported, clean up and bail immediately.
	 */
	useEffect(
		function () {
			if ( supported ) {
				return;
			}

			if ( ENFORCE ) {
				unlockPostSaving( LOCK_KEY );
			}
			removeNotice( NOTICE_ID_REQUIRED );
			removeNotice( NOTICE_ID_SUGGESTED );
		},
		[ supported, unlockPostSaving, removeNotice ]
	);

	/**
	 * Bail from rendering as well.
	 */
	if ( ! supported ) {
		return null;
	}

	/**
	 * Only run heavier selects for supported post types.
	 */
	const state = useSelect(
		function ( select ) {
			const editorSelect = select( 'core/editor' );
			const featuredId = editorSelect.getEditedPostAttribute( 'featured_media' ) || 0;

			return {
				blocks: select( 'core/block-editor' ).getBlocks() || [],
				categories: editorSelect.getEditedPostAttribute( 'categories' ) || [],
				tags: editorSelect.getEditedPostAttribute( 'tags' ) || [],
				featuredImageId: featuredId,
				featuredMedia: featuredId ? select( 'core' ).getMedia( featuredId ) : null,
			};
		},
		[]
	);

	const evaluated = useMemo(
		function () {
			return evaluateConditions( state );
		},
		[ state ]
	);

	const requiredFailures = evaluated.filter( function ( item ) {
		return isRequired( item.type ) && ! item.complete;
	} );

	const suggestedFailures = evaluated.filter( function ( item ) {
		return isSuggested( item.type ) && ! item.complete;
	} );

	const shouldLock = requiredFailures.length > 0;

	useEffect(
		function () {
			if ( shouldLock ) {
				if ( ENFORCE ) {
					lockPostSaving( LOCK_KEY );
				}

				createNotice(
					'error',
					buildNoticeMessage(
						requiredFailures,
						__( 'Cannot publish yet:', 'site-functionality' )
					),
					{
						id: NOTICE_ID_REQUIRED,
						type: 'default',
						isDismissible: false,
						__unstableHTML: true,
					}
				);
			} else {
				if ( ENFORCE ) {
					unlockPostSaving( LOCK_KEY );
				}
				removeNotice( NOTICE_ID_REQUIRED );
			}

			if ( suggestedFailures.length ) {
				createNotice(
					'warning',
					buildNoticeMessage(
						suggestedFailures,
						__( 'Suggestion:', 'site-functionality' )
					),
					{
						id: NOTICE_ID_SUGGESTED,
						type: 'default',
						isDismissible: true,
						__unstableHTML: true,
					}
				);
			} else {
				removeNotice( NOTICE_ID_SUGGESTED );
			}
		},
		[
			shouldLock,
			requiredFailures,
			suggestedFailures,
			lockPostSaving,
			unlockPostSaving,
			createNotice,
			removeNotice,
		]
	);

	return (
		<PluginPrePublishPanel
			title={ __( 'Publish Checklist', 'site-functionality' ) }
			initialOpen={ true }
			icon={ null }
		>
			{ evaluated.map( function ( item ) {
				return (
					<PanelRow key={ item.key }>
						<CheckboxControl
							label={ item.label }
							checked={ item.complete }
							disabled
							help={ item.complete ? item.messages.complete : item.messages.incomplete }
						/>
					</PanelRow>
				);
			} ) }
		</PluginPrePublishPanel>
	);
};

registerPlugin( 'pre-publish-checklist', {
	render: PrePublishChecklist,
	icon: 'yes',
} );
