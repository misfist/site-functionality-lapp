import { addFilter } from '@wordpress/hooks';
import { PostTaxonomiesHierarchicalTermSelector as HierarchicalTermSelector } from '@wordpress/editor';

function hierarchicalTermSelector( OriginalComponent ) {
    return function( props ) {
        if ( 'post_tag' !== props.slug ) {
        	return <OriginalComponent { ...props } />;
        }

        return (
			<div className="is-hierarchical-post-tags">
				<HierarchicalTermSelector { ...props } />
			</div>
		);
    };
}

addFilter( 
    'editor.PostTaxonomyType', 
    'site-functionality/hierarchical-term-selector', 
    hierarchicalTermSelector
);