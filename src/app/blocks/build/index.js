/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/cta-slot/block.json":
/*!*********************************!*\
  !*** ./src/cta-slot/block.json ***!
  \*********************************/
/***/ ((module) => {

module.exports = /*#__PURE__*/JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"site-functionality/cta-slot","version":"0.1.0","title":"Global CTA Slot","category":"lapp","icon":"format-image","description":"Displays the globally configured CTA.","attributes":{"anchor":{"type":"string"}},"example":{},"supports":{"html":false,"anchor":true,"align":["wide","full","center","left","right"],"color":{"background":true}},"textdomain":"site-functionality","editorScript":"file:./index.js","editorStyle":"file:./index.css","style":"file:./style-index.css","render":"file:./render.php","viewScript":"file:./view.js"}');

/***/ }),

/***/ "./src/cta-slot/edit.js":
/*!******************************!*\
  !*** ./src/cta-slot/edit.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./editor.scss */ "./src/cta-slot/editor.scss");

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */


/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */




/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */


/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
const Edit = props => {
  const {
    attributes,
    setAttributes
  } = props;
  const {
    anchor
  } = attributes;
  const blockProps = (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.useBlockProps)({
    'id': anchor
  });
  const [html, setHtml] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)('');
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(() => {
    if (!anchor) {
      setAttributes({
        anchor: `cta-slot-${crypto.randomUUID()}`
      });
    }
  }, [anchor, setAttributes]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(() => {
    _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({
      path: '/site-functionality/v1/cta-slot'
    }).then(response => setHtml(response?.html || '')).catch(() => setHtml(''));
  }, []);
  if (!html) {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      ...blockProps
    }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('No ad selected in Ad Settings.', 'site-functionality'));
  }
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    ...blockProps
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      pointerEvents: 'none'
    },
    dangerouslySetInnerHTML: {
      __html: html
    }
  }));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (Edit);

/***/ }),

/***/ "./src/cta-slot/editor.scss":
/*!**********************************!*\
  !*** ./src/cta-slot/editor.scss ***!
  \**********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/cta-slot/index.js":
/*!*******************************!*\
  !*** ./src/cta-slot/index.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./style.scss */ "./src/cta-slot/style.scss");
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./edit */ "./src/cta-slot/edit.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./block.json */ "./src/cta-slot/block.json");
/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */


/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */


/**
 * Internal dependencies
 */



/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)(_block_json__WEBPACK_IMPORTED_MODULE_3__.name, {
  /**
   * @see ./edit.js
   */
  edit: _edit__WEBPACK_IMPORTED_MODULE_2__["default"]
});

/***/ }),

/***/ "./src/cta-slot/style.scss":
/*!*********************************!*\
  !*** ./src/cta-slot/style.scss ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/hooks/extend/index.js":
/*!***********************************!*\
  !*** ./src/hooks/extend/index.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _taxonomy_ui__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./taxonomy-ui */ "./src/hooks/extend/taxonomy-ui/index.js");
// import './checklist';


/***/ }),

/***/ "./src/hooks/extend/taxonomy-ui/index.js":
/*!***********************************************!*\
  !*** ./src/hooks/extend/taxonomy-ui/index.js ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/editor */ "@wordpress/editor");
/* harmony import */ var _wordpress_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_editor__WEBPACK_IMPORTED_MODULE_2__);



function hierarchicalTermSelector(OriginalComponent) {
  return function (props) {
    if ('post_tag' !== props.slug) {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(OriginalComponent, {
        ...props
      });
    }
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "is-hierarchical-post-tags"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_editor__WEBPACK_IMPORTED_MODULE_2__.PostTaxonomiesHierarchicalTermSelector, {
      ...props
    }));
  };
}
(0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_1__.addFilter)('editor.PostTaxonomyType', 'site-functionality/hierarchical-term-selector', hierarchicalTermSelector);

/***/ }),

/***/ "./src/hooks/index.js":
/*!****************************!*\
  !*** ./src/hooks/index.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _extend__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./extend */ "./src/hooks/extend/index.js");


/***/ }),

/***/ "./src/plugins/pre-publish-checklist/index.js":
/*!****************************************************!*\
  !*** ./src/plugins/pre-publish-checklist/index.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/editor */ "@wordpress/editor");
/* harmony import */ var _wordpress_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_editor__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/plugins */ "@wordpress/plugins");
/* harmony import */ var _wordpress_plugins__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_plugins__WEBPACK_IMPORTED_MODULE_6__);

/**
 * WordPress dependencies.
 */






const LOCK_KEY = 'site-functionality.prepublish-checks';
const ENFORCE = true; // true = lock saving, false = notices only

const REQUIRED_BLOCK = 'site-functionality/cta-slot';
const MIN_FEATURED_WIDTH = 1200;
const MIN_FEATURED_HEIGHT = 675;
const NOTICE_ID_REQUIRED = 'site-functionality.prepublish-checks.required';
const NOTICE_ID_SUGGESTED = 'site-functionality.prepublish-checks.suggested';
const SUPPORTED_POST_TYPES = ['post'];

/**
 * Condition configuration.
 */
const CONDITIONS = {
  block: {
    condition: 'suggested',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('CTA Slot block present', 'site-functionality'),
    icon: 'no-alt',
    messages: {
      complete: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('CTA Slot block is present.', 'site-functionality'),
      incomplete: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Consider adding a CTA block.', 'site-functionality'),
      error: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Unable to verify whether the CTA Slot block is present.', 'site-functionality')
    }
  },
  image: {
    condition: 'required',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Featured Image set', 'site-functionality'),
    messages: {
      complete: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Featured Image is set.', 'site-functionality'),
      incomplete: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('A Featured Image is required.', 'site-functionality'),
      error: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Unable to verify whether a Featured Image is set.', 'site-functionality')
    }
  },
  min_dimensions: {
    condition: 'required',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Featured Image meets minimum size', 'site-functionality'),
    messages: {
      complete: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Featured Image meets the minimum size.', 'site-functionality'),
      incomplete: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.sprintf)(/* translators: 1: min width, 2: min height */
      (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Featured Image must be at least %1$d × %2$d.', 'site-functionality'), MIN_FEATURED_WIDTH, MIN_FEATURED_HEIGHT),
      error: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Unable to verify Featured Image dimensions.', 'site-functionality')
    }
  },
  category: {
    condition: 'required',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('At least one Category is selected', 'site-functionality'),
    messages: {
      complete: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('At least one Category is selected.', 'site-functionality'),
      incomplete: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('At least one Category is required.', 'site-functionality'),
      error: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Unable to verify Categories.', 'site-functionality')
    }
  },
  tag: {
    condition: 'suggested',
    icon: 'no-alt',
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Tags have been added', 'site-functionality'),
    messages: {
      complete: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Tags have been added.', 'site-functionality'),
      incomplete: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Consider adding Tags to improve discoverability.', 'site-functionality'),
      error: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Unable to verify Tags.', 'site-functionality')
    }
  }
};

/**
 * Determine whether a post type is supported.
 *
 * @param {string} postType Post type.
 * @return {boolean} True if supported.
 */
function isSupportedPostType(postType) {
  return SUPPORTED_POST_TYPES.indexOf(postType) !== -1;
}

/**
 * Recursively check whether a block (or any inner block) matches a block name.
 *
 * @param {Array}  blocks    Block array.
 * @param {string} blockName Block name to find.
 * @return {boolean} True if found.
 */
function hasBlock(blocks, blockName) {
  if (!Array.isArray(blocks) || !blockName) {
    return false;
  }
  return blocks.some(function (block) {
    if (block && block.name === blockName) {
      return true;
    }
    if (block && Array.isArray(block.innerBlocks) && block.innerBlocks.length) {
      return hasBlock(block.innerBlocks, blockName);
    }
    return false;
  });
}

/**
 * Check if a featured image exists.
 *
 * @param {number} featuredImageId Media ID.
 * @return {boolean} True if present.
 */
function hasImage(featuredImageId) {
  return Number(featuredImageId) > 0;
}

/**
 * Get image dimensions from a media object.
 *
 * @param {Object|null} media Media object from core store.
 * @return {{width:number|null,height:number|null}} Dimensions.
 */
function getMediaDimensions(media) {
  if (!media || !media.media_details || !media.media_details.width || !media.media_details.height) {
    return {
      width: null,
      height: null
    };
  }
  return {
    width: media.media_details.width,
    height: media.media_details.height
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
function hasMinDimensions(media, minWidth, minHeight) {
  const dims = getMediaDimensions(media);
  if (!dims.width || !dims.height) {
    return true;
  }
  return dims.width >= Number(minWidth) && dims.height >= Number(minHeight);
}

/**
 * Check if any term exists for a taxonomy field (category, tag, etc).
 *
 * @param {Array} terms Term IDs.
 * @return {boolean} True if at least one term.
 */
function hasTerm(terms) {
  return Array.isArray(terms) && terms.length > 0;
}

/**
 * Alias: Check for at least one category term.
 *
 * @param {Array} categories Category term IDs.
 * @return {boolean} True if at least one category.
 */
function hasCategory(categories) {
  return hasTerm(categories);
}

/**
 * Alias: Check for at least one tag term.
 *
 * @param {Array} tags Tag term IDs.
 * @return {boolean} True if at least one tag.
 */
function hasTag(tags) {
  return hasTerm(tags);
}

/**
 * Check whether a condition type is required.
 *
 * @param {string} conditionType Condition type.
 * @return {boolean} True if required.
 */
function isRequired(conditionType) {
  return conditionType === 'required';
}

/**
 * Check whether a condition type is suggested.
 *
 * @param {string} conditionType Condition type.
 * @return {boolean} True if suggested.
 */
function isSuggested(conditionType) {
  return conditionType === 'suggested';
}

/**
 * Evaluate all conditions.
 *
 * @param {Object} state Editor state.
 * @return {Array} List of evaluated condition results.
 */
function evaluateConditions(state) {
  return [{
    key: 'block',
    complete: hasBlock(state.blocks, REQUIRED_BLOCK)
  }, {
    key: 'image',
    complete: hasImage(state.featuredImageId)
  }, {
    key: 'min_dimensions',
    complete: hasImage(state.featuredImageId) && hasMinDimensions(state.featuredMedia, MIN_FEATURED_WIDTH, MIN_FEATURED_HEIGHT)
  }, {
    key: 'category',
    complete: hasCategory(state.categories)
  }, {
    key: 'tag',
    complete: hasTag(state.tags)
  }].map(function (result) {
    const config = CONDITIONS[result.key];
    return {
      key: result.key,
      type: config.condition,
      label: config.label,
      icon: config.icon,
      complete: Boolean(result.complete),
      messages: config.messages
    };
  });
}

/**
 * Build a single-line notice message for a set of failures.
 *
 * @param {Array} failures Failed condition results.
 * @param {string} heading Notice heading.
 * @return {string} Message.
 */
function buildNoticeMessage(failures, heading) {
  const items = failures.map(function (item) {
    const icon = item.icon || 'warning';
    return '<li>' + '<span class="dashicons dashicons-' + icon + '" style="margin-right:6px;vertical-align:middle;"></span>' + item.messages.incomplete + '</li>';
  }).join('');
  return '<strong>' + heading + '</strong>' + '<ul style="margin:8px 0 0 0">' + items + '</ul>';
}

/**
 * Pre-publish checklist plugin.
 *
 * Evaluates required and suggested publishing conditions for supported post types,
 * displays a checklist in the pre-publish panel, surfaces editor notices for unmet
 * conditions, and optionally enforces requirements by locking post saving.
 *
 * @return {JSX.Element|null} Plugin UI, or null when unsupported.
 */
const PrePublishChecklist = () => {
  /**
   * Select postType first so we can bail early and avoid unnecessary selectors.
   */
  const postType = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.useSelect)(function (select) {
    return select('core/editor').getCurrentPostType();
  }, []);
  const supported = isSupportedPostType(postType);
  const {
    lockPostSaving,
    unlockPostSaving
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.useDispatch)('core/editor');
  const {
    createNotice,
    removeNotice
  } = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.useDispatch)('core/notices');

  /**
   * If the post type is not supported, clean up and bail immediately.
   */
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(function () {
    if (supported) {
      return;
    }
    if (ENFORCE) {
      unlockPostSaving(LOCK_KEY);
    }
    removeNotice(NOTICE_ID_REQUIRED);
    removeNotice(NOTICE_ID_SUGGESTED);
  }, [supported, unlockPostSaving, removeNotice]);
  if (!supported) {
    return null;
  }
  const state = (0,_wordpress_data__WEBPACK_IMPORTED_MODULE_2__.useSelect)(function (select) {
    const editorSelect = select('core/editor');
    const featuredId = editorSelect.getEditedPostAttribute('featured_media') || 0;
    return {
      blocks: select('core/block-editor').getBlocks() || [],
      categories: editorSelect.getEditedPostAttribute('categories') || [],
      tags: editorSelect.getEditedPostAttribute('tags') || [],
      featuredImageId: featuredId,
      featuredMedia: featuredId ? select('core').getMedia(featuredId) : null
    };
  }, []);
  const evaluated = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useMemo)(function () {
    return evaluateConditions(state);
  }, [state]);
  const requiredFailures = evaluated.filter(function (item) {
    return isRequired(item.type) && !item.complete;
  });
  const suggestedFailures = evaluated.filter(function (item) {
    return isSuggested(item.type) && !item.complete;
  });
  const shouldLock = requiredFailures.length > 0;
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useEffect)(function () {
    if (shouldLock) {
      if (ENFORCE) {
        lockPostSaving(LOCK_KEY);
      }
      createNotice('error', buildNoticeMessage(requiredFailures, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Cannot publish yet:', 'site-functionality')), {
        id: NOTICE_ID_REQUIRED,
        type: 'default',
        isDismissible: false,
        __unstableHTML: true
      });
    } else {
      if (ENFORCE) {
        unlockPostSaving(LOCK_KEY);
      }
      removeNotice(NOTICE_ID_REQUIRED);
    }
    if (suggestedFailures.length) {
      createNotice('warning', buildNoticeMessage(suggestedFailures, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Suggestion:', 'site-functionality')), {
        id: NOTICE_ID_SUGGESTED,
        type: 'default',
        isDismissible: true,
        __unstableHTML: true
      });
    } else {
      removeNotice(NOTICE_ID_SUGGESTED);
    }
  }, [shouldLock, requiredFailures, suggestedFailures, lockPostSaving, unlockPostSaving, createNotice, removeNotice]);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_editor__WEBPACK_IMPORTED_MODULE_1__.PluginPrePublishPanel, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Publish Checklist', 'site-functionality'),
    initialOpen: true,
    icon: null
  }, evaluated.map(function (item) {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.PanelRow, {
      key: item.key
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.CheckboxControl, {
      label: item.label,
      checked: item.complete,
      disabled: true,
      help: item.complete ? item.messages.complete : item.messages.incomplete
    }));
  }));
};
(0,_wordpress_plugins__WEBPACK_IMPORTED_MODULE_6__.registerPlugin)('pre-publish-checklist', {
  render: PrePublishChecklist,
  icon: 'yes'
});

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ ((module) => {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["data"];

/***/ }),

/***/ "@wordpress/editor":
/*!********************************!*\
  !*** external ["wp","editor"] ***!
  \********************************/
/***/ ((module) => {

module.exports = window["wp"]["editor"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/hooks":
/*!*******************************!*\
  !*** external ["wp","hooks"] ***!
  \*******************************/
/***/ ((module) => {

module.exports = window["wp"]["hooks"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "@wordpress/plugins":
/*!*********************************!*\
  !*** external ["wp","plugins"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["plugins"];

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"index": 0,
/******/ 			"./style-index": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = globalThis["webpackChunksite_functionality"] = globalThis["webpackChunksite_functionality"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["./style-index"], () => (__webpack_require__("./src/cta-slot/index.js")))
/******/ 	__webpack_require__.O(undefined, ["./style-index"], () => (__webpack_require__("./src/hooks/index.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["./style-index"], () => (__webpack_require__("./src/plugins/pre-publish-checklist/index.js")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map