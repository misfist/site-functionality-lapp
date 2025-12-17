import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';
import { useEffect, useRef, useState  } from '@wordpress/element';
import { useSelect, useDispatch } from '@wordpress/data';

function FeaturedImageChecklistItem( OriginalComponent ) {
	return function Wrapper( props ) {

		const featuredMediaId = useSelect(
			( select ) => select( 'core/editor' ).getEditedPostAttribute( 'featured_media' ),
			[]
		);

		const hasFeaturedImage = Boolean( featuredMediaId );

		const status = hasFeaturedImage  ? 'complete' : 'incomplete';

		const message = hasFeaturedImage 
			? __( 'Featured image is set', 'site-functionality' )
			: __( 'Add a featured image', 'site-functionality' );

		const nextProps = {
			...props,
			status,
			message,
		};

        console.log( 'hasFeaturedImage', hasFeaturedImage );

        console.log( 'Publication Checklist item props:', nextProps );

		return <OriginalComponent { ...nextProps } />;
	};
}

addFilter(
	'altis-publishing-workflow.item.featured-image',
	'site-functionality/featured-image-check',
	FeaturedImageChecklistItem
);
