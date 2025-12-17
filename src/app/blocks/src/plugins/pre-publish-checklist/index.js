/**
 * WordPress dependencies.
 */
import { PluginPrePublishPanel } from '@wordpress/editor';
import { useSelect, useDispatch } from '@wordpress/data';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { CheckboxControl, PanelRow, Notice } from '@wordpress/components';
import { registerPlugin } from '@wordpress/plugins';

const LOCK_KEY = 'site-functionality.prepublish-checks';
const REQUIRED_BLOCK = 'site-functionality/cta-slot';
const MIN_FEATURED_WIDTH = 1200;
const MIN_FEATURED_HEIGHT = 675;

/**
 * Recursively check whether a block (or any inner block) matches a block name.
 *
 * @param {Array}  blocks    Block array.
 * @param {string} blockName Block name to find.
 * @return {boolean} True if found.
 */
function hasBlock( blocks, blockName ) {
	return blocks.some( function ( block ) {
		if ( block.name === blockName ) {
			return true;
		}

		if ( block.innerBlocks && block.innerBlocks.length ) {
			return hasBlock( block.innerBlocks, blockName );
		}

		return false;
	} );
}

/**
 * Extract image dimensions from the media record, where available.
 *
 * @param {Object|null} media Media object from core store.
 * @return {{width:number|null,height:number|null}}
 */
function getMediaDimensions( media ) {
	if ( ! media ) {
		return { width: null, height: null };
	}

	// Prefer "media_details.width/height" when present.
	if ( media.media_details && media.media_details.width && media.media_details.height ) {
		return {
			width: media.media_details.width,
			height: media.media_details.height,
		};
	}

	return { width: null, height: null };
}

const PrePublishChecklist = () => {
	const {
		postType,
		blocks,
		categories,
		tags,
		featuredImageId,
		featuredMedia,
	} = useSelect(
		function ( select ) {
			const editorSelect = select( 'core/editor' );

			const featuredId = editorSelect.getEditedPostAttribute( 'featured_media' ) || 0;

			return {
				postType: editorSelect.getCurrentPostType(),
				blocks: select( 'core/block-editor' ).getBlocks() || [],
				categories: editorSelect.getEditedPostAttribute( 'categories' ) || [],
				tags: editorSelect.getEditedPostAttribute( 'tags' ) || [],
				featuredImageId: featuredId,
				featuredMedia: featuredId ? select( 'core' ).getMedia( featuredId ) : null,
			};
		},
		[]
	);

	const { lockPostSaving, unlockPostSaving } = useDispatch( 'core/editor' );

	// Optional: limit to specific post types. Remove this block if you want it everywhere.
	if ( postType !== 'post' ) {
		useEffect(
			() => {
				unlockPostSaving( LOCK_KEY );
			},
			[ unlockPostSaving ]
		);

		return null;
	}

	const hasRequiredBlock = hasBlock( blocks, REQUIRED_BLOCK );

	const hasFeaturedImage = featuredImageId > 0;

	const { width: featuredWidth, height: featuredHeight } = getMediaDimensions( featuredMedia );

	const meetsFeaturedSize =
		! hasFeaturedImage ||
		( featuredWidth && featuredHeight
			? featuredWidth >= MIN_FEATURED_WIDTH && featuredHeight >= MIN_FEATURED_HEIGHT
			: true );

	const hasCategory = categories.length > 0;

	const hasTags = tags.length > 0;

	const shouldLock =
		! hasRequiredBlock ||
		! hasFeaturedImage ||
		! meetsFeaturedSize ||
		! hasCategory;

	useEffect(
		function () {
			if ( shouldLock ) {
				lockPostSaving( LOCK_KEY );
				return;
			}

			unlockPostSaving( LOCK_KEY );
		},
		[ shouldLock, lockPostSaving, unlockPostSaving ]
	);

	console.log( 'LOADED' );

	return (
		<PluginPrePublishPanel
			title={ __( 'Publish Checklist', 'site-functionality' ) }
			initialOpen={ true }
			icon={ null }
		>
			{ ! hasRequiredBlock && (
				<PanelRow>
					<Notice status="error" isDismissible={ false }>
						{ __(
							'CTA block is required.',
							'site-functionality'
						) }
					</Notice>
				</PanelRow>
			) }

			{ ! hasFeaturedImage && (
				<PanelRow>
					<Notice status="error" isDismissible={ false }>
						{ __( 'A Featured Image is required.', 'site-functionality' ) }
					</Notice>
				</PanelRow>
			) }

			{ hasFeaturedImage && ! meetsFeaturedSize && (
				<PanelRow>
					<Notice status="error" isDismissible={ false }>
						{ __(
							'The selected Featured Image is too small. Please use an image at least 1200 × 675.',
							'site-functionality'
						) }
					</Notice>
				</PanelRow>
			) }

			{ ! hasCategory && (
				<PanelRow>
					<Notice status="error" isDismissible={ false }>
						{ __(
							'At least one Category is required.',
							'site-functionality'
						) }
					</Notice>
				</PanelRow>
			) }

			{ ! hasTags && (
				<PanelRow>
					<Notice 
						status="warning" 
						isDismissible={ true }
					>
						{ __( 'Consider adding Tags to improve discoverability.', 'site-functionality' ) }
					</Notice>
				</PanelRow>
			) }

			<PanelRow>
				<CheckboxControl
					label={ __( 'CTA Slot block present', 'site-functionality' ) }
					checked={ hasRequiredBlock }
					disabled
				/>
			</PanelRow>

			<PanelRow>
				<CheckboxControl
					label={ __( 'Featured Image set', 'site-functionality' ) }
					checked={ hasFeaturedImage }
					disabled
				/>
			</PanelRow>

			<PanelRow>
				<CheckboxControl
					label={ __( 'Featured Image meets minimum size', 'site-functionality' ) }
					checked={ Boolean( meetsFeaturedSize && hasFeaturedImage ) }
					disabled
				/>
			</PanelRow>

			<PanelRow>
				<CheckboxControl
					label={ __( 'At least one Category is selected', 'site-functionality' ) }
					checked={ hasCategory }
					disabled
				/>
			</PanelRow>

			<PanelRow>
				<CheckboxControl
					label={ __( 'Tags gave been added', 'site-functionality' ) }
					checked={ hasTags }
					disabled
				/>
			</PanelRow>
		</PluginPrePublishPanel>
	);
};

registerPlugin( 'pre-publish-checklist', {
	render: PrePublishChecklist,
	icon: 'yes',
} );
