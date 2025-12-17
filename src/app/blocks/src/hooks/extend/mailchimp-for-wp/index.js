import { addFilter } from '@wordpress/hooks';

const BLOCK_NAME = 'mailchimp-for-wp/form';

function siteFunctionalityAddMailchimpAlignSupport( settings, name ) {
	if ( BLOCK_NAME  !== name ) {
		return settings;
	}

	return {
		...settings,
		supports: {
			...settings.supports,
			align: [ 'center' ],
		},
	};
}

addFilter(
	'blocks.registerBlockType',
	'site-functionality/mailchimp-align-center',
	siteFunctionalityAddMailchimpAlignSupport
);