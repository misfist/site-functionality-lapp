import { PostTaxonomiesHierarchicalTermSelector as HierarchicalTermSelector } from '@wordpress/editor';

function hierarchicalTermSelector( OriginalComponent ) {
    return function( props ) {
        if ( 'post_tag' !== props.slug ) {
        	return <OriginalComponent { ...props } />;
        }

        return <HierarchicalTermSelector { ...props } />;
    };
}

wp.hooks.addFilter( 
    'editor.PostTaxonomyType', 
    'site-functionality/hierarchical-term-selector', 
    hierarchicalTermSelector
);