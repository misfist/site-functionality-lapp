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
import { useBlockProps } from '@wordpress/block-editor';

import { useEffect, useState } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

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
const Edit = ( props ) => {
	const { attributes, setAttributes } = props;
	const { anchor } = attributes;
	const blockProps = useBlockProps( {
		'id': anchor
	} );

	const [ html, setHtml ] = useState( '' );

	useEffect( () => {
		if ( ! anchor ) {
			setAttributes( { anchor: `ad-slot-${ crypto.randomUUID() }` } );
		}
	}, [ anchor, setAttributes ] );

	useEffect( () => {
		apiFetch( { path: '/site-functionality/v1/ad-slot' } )
			.then( ( response ) => setHtml( response?.html || '' ) )
			.catch( () => setHtml( '' ) );
	}, [] );

	if ( ! html ) {
		return (
			<div { ...blockProps }>
				{ __( 'No ad selected in Ad Settings.', 'site-functionality' ) }
			</div>
		);
	}

	return (
		<div { ...blockProps }>
			<div style={ { pointerEvents: 'none' } } dangerouslySetInnerHTML={ { __html: html } } />
		</div>
	);
};

export default Edit;
