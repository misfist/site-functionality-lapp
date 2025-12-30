/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { 
	useBlockProps,	
	InspectorControls 
} from '@wordpress/block-editor';

import { 
	PanelBody,
	SelectControl,
	TextControl
} from '@wordpress/components';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';

/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit( {
	attributes: { objectId, objectType },
	setAttributes,
} ) {

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Sponsor Details', 'lapp' ) }>
					<TextControl
						__next40pxDefaultSize
						label={ __( 'Object Id', 'lapp' ) }
						value={ objectId }
						onChange={ ( value ) => setAttributes( { 
							objectId: value
						} ) }
					/>
					<SelectControl
						label={ __( 'Page Type', 'lapp' ) }
						value={ objectType }
						options={ [
							{ 
								label: __( 'Archive', 'lapp' ), 
								value: 'term' 
							},
							{ 
								label: __( 'Single Post', 'lapp' ), 
								value: 'post'
							}
						] }
						onChange={ ( value ) => setAttributes( { 
							objectType: value
						} ) }
						__next40pxDefaultSize
					/>
				</PanelBody>
			</InspectorControls>
			<div{ ...useBlockProps() }>
				{ __( 'Sponsor Information', 'lapp' ) }
			</div>
		</>
	);
}
