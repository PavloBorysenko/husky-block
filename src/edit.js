import { useState, useEffect} from "react";
import {
	TextControl,
	RangeControl,
	SelectControl,
	PanelBody,
	Panel,
	ServerSideRender,
	PanelRow,
	ColorPalette,
	ColorPicker,
} from "@wordpress/components";

/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-i18n/
 */
import { __ } from "@wordpress/i18n";

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import "./editor.scss";
import By_items from "./data/By_items";
import Taxonomies from "./data/Taxonomies";



/**
 * by_only
 * tax_only
 * tax_exclude
 * taxonomies
 * autosubmit
 * dynamic_recount
 * is_ajax
 * hide_terms_count_txt
 * ajax_redraw
 * redirect 
 * autohide
 * start_filtering_btn
 * btn_position
 * mobile_mode
 * background
 * columns lg
 * columns md
 * columns sm
 * conditionals
 * padding
 * 
 */


/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @return {Element} Element to render.
 */
export default function Edit(props) {
//https://wordpress.stackexchange.com/questions/391371/how-to-load-an-additional-script-for-a-block-in-the-block-editor
	const { attributes, setAttributes } = props;
	const {
		autosubmit,
		dynamic_recount,
		by_only,
		tax_only,
		tax_exclude,
		is_ajax,
		ajax_redraw,
		hide_terms_count_txt,
		redirect,
		autohide,
		start_filtering_btn,
		btn_position,
		taxonomies,
		mobile_mode,
		columns_lg,
		columns_md,
		columns_sm,
		conditionals,
		padding,
		max_height,

	} = attributes;
	const yesLabel = __("Yes", "husky-filter-block");
	const noLabel = __("No", "husky-filter-block");
	const defaultLabel = __("Default", "husky-filter-block");

	const allTaxonomies = Taxonomies();
	const allBy_items = By_items();
	const { serverSideRender: ServerSideRender } = wp;


	return (
		<div {...useBlockProps()}>
			
				<ServerSideRender
				 	key="editable"
                    block={props.name}
					attributes={{
						'autosubmit': props.attributes.autosubmit,
						'ajax_redraw': props.attributes.ajax_redraw,
						'by_only': props.attributes.by_only,
						'tax_only': props.attributes.tax_only,
						'tax_exclude': props.attributes.tax_exclude,
						'autohide': props.attributes.autohide,
						'start_filtering_btn': props.attributes.start_filtering_btn,
						'btn_position': props.attributes.btn_position,
						'columns_lg': props.attributes.columns_lg,
						'columns_md': props.attributes.columns_md,
						'columns_sm': props.attributes.columns_sm,
						'padding': props.attributes.padding,
						'max_height': props.attributes.max_height,
					}}
                />
			<InspectorControls>
			<div>
				<SelectControl
					label={__("Autosubmit", "husky-filter-block")}
					value={autosubmit}
					options={[
						{ label: defaultLabel, value: -1 },
						{ label: noLabel, value: 0 },
						{ label: yesLabel, value: 1 },
					]}
					onChange={(nextValue) => setAttributes({ autosubmit: nextValue })}
				/>
			</div>
			<div>
				<SelectControl
					label={__("Dynamic recount", "husky-filter-block")}
					value={dynamic_recount}
					options={[
						{ label: defaultLabel, value: -1 },
						{ label: noLabel, value: 0 },
						{ label: yesLabel, value: 1 },
					]}
					onChange={(nextValue) =>
						setAttributes({ dynamic_recount: nextValue })
					}
				/>
			</div>	
			<div>
				<SelectControl
					label={__("is_ajax", "husky-filter-block")}
					value={is_ajax}
					options={[
						{ label: noLabel, value: 0 },
						{ label: yesLabel, value: 1 },
					]}
					onChange={(nextValue) => setAttributes({ is_ajax: nextValue })}
				/>
			</div>
			<Panel header="Advanced options">
			<PanelBody title={__("Additional", "husky-filter-block")}  initialOpen={ false }>
				<div>
					<SelectControl
						label={__("ajax_redraw", "husky-filter-block")}
						value={ajax_redraw}
						options={[
							{ label: noLabel, value: 0 },
							{ label: yesLabel, value: 1 },
						]}
						onChange={(nextValue) => setAttributes({ ajax_redraw: nextValue })}
					/>
				</div>
				<div>
					<SelectControl
						label={__("Hide count", "husky-filter-block")}
						help={__(
							'hide text with count of variants, Yes or No. Doesn work in free version!',
							"husky-filter-block",
						)}
						value={hide_terms_count_txt}
						options={[
							{ label: noLabel, value: 0 },
							{ label: yesLabel, value: 1 },
						]}
						onChange={(nextValue) =>
							setAttributes({ hide_terms_count_txt: nextValue })
						}
					/>
				</div>
				<div>
					<TextControl
						label={__("Redirect to", "husky-filter-block")}
						value={redirect}
						onChange={(nextValue) => setAttributes({ redirect: nextValue })}
					/>
				</div>
				<div>
					<TextControl
						label={__("taxonomies", "husky-filter-block")}
						help={__(
							'uses to display relevant filter-items in generated search form if activated: show count+dynamic recount+hide empty options in the plugin settings.',
							"husky-filter-block",
						)}
						value={taxonomies}
						onChange={(nextValue) => setAttributes({ taxonomies: nextValue })}
					/>
				</div>
				<div>
					<TextControl
						label={__("conditionals", "husky-filter-block")}
						help={__(
							'special attribute for extension ‘Conditionals‘ which allows to define the conditions for displaying filter elements depending of the current filtering request.',
							"husky-filter-block",
						)}
						value={conditionals}
						onChange={(nextValue) => setAttributes({ conditionals: nextValue })}
					/>
				</div>			
			</PanelBody>
			<PanelBody title={__("Setting up filter elements", "husky-filter-block")}  initialOpen={ false }>
				<div>
					<SelectControl
						multiple
						label={__("tax_only", "husky-filter-block")}
						value={tax_only}
						options={allTaxonomies}
						onChange={(nextValue) => setAttributes({ tax_only: nextValue })}
					/>
				</div>
				<div>
					<SelectControl
						multiple
						label={__("by_only", "husky-filter-block")}
						value={by_only}
						options={allBy_items}
						onChange={(nextValue) => setAttributes({ by_only: nextValue })}
					/>
				</div>
				<div>
					<SelectControl
						multiple
						label={__("tax_exclude", "husky-filter-block")}
						value={tax_exclude}
						options={allTaxonomies.concat( allBy_items)}
						onChange={(nextValue) => setAttributes({ tax_exclude: nextValue })}
					/>
				</div>
			</PanelBody>
			<PanelBody title={__("Filter display type", "husky-filter-block")}  initialOpen={ false }>
				<div>
					<SelectControl
						label={__("Autohide", "husky-filter-block")}
						value={autohide}
						options={[
							{ label: noLabel, value: 0 },
							{ label: yesLabel, value: 1 },
						]}
						onChange={(nextValue) =>
							setAttributes({ autohide: nextValue })
						}
					/>
				</div>
				<div>
					<SelectControl
						label={__("Hide search form by default", "husky-filter-block")}
						help={__(
							'User on the site front will have to press button like "Show products filter form" to load search form by ajax and start filtering. Good feature when search form is quite big and page loading takes more time because of it!',
							"husky-filter-block",
						)}
						value={start_filtering_btn}
						options={[
							{ label: noLabel, value: 0 },
							{ label: yesLabel, value: 1 },
						]}
						onChange={(nextValue) => setAttributes({start_filtering_btn: nextValue })}
					/>
				</div>
				<div>
					<SelectControl
						label={__("Submit button position", "husky-filter-block")}
						value={btn_position}
						options={[
							{ label: __("Bottom", "husky-filter-block"), value: "b" },
							{ label: __("Top", "husky-filter-block"), value: "t" },
							{ label: __("Top&Bottom", "husky-filter-block"), value: "tb" },
						]}
						onChange={(nextValue) => setAttributes({ btn_position: nextValue })}
					/>
				</div>
				<div>
					<SelectControl
						label={__("Mobile mode", "husky-filter-block")}
						value={mobile_mode}
						options={[
							{ label: noLabel, value: 0 },
							{ label: yesLabel, value: 1 },
						]}
						onChange={(nextValue) => setAttributes({ mobile_mode: nextValue })}
					/>				
				</div>											
			</PanelBody>
			</Panel>
			
				<PanelBody title={__("Filter columns", "husky-filter-block")}  initialOpen={ false }>
					<div>
						<SelectControl
							label={__("Wide screen", "husky-filter-block")}
							value={columns_lg}
							options={[
								{ label: 1, value: 1 },
								{ label: 2, value: 2 },
								{ label: 3, value: 3 },
								{ label: 4, value: 4 },
							]}
							onChange={(nextValue) => setAttributes({ columns_lg: nextValue })}
						/>				
					</div>
					<div>
						<SelectControl
							label={__("Medium screens", "husky-filter-block")}
							value={columns_md}
							options={[
								{ label: 1, value: 1 },
								{ label: 2, value: 2 },
								{ label: 3, value: 3 },
								{ label: 4, value: 4 },
							]}
							onChange={(nextValue) => setAttributes({ columns_md: nextValue })}
						/>				
					</div>
					<div>
						<SelectControl
							label={__("Small screens", "husky-filter-block")}
							value={columns_sm}
							options={[
								{ label: 1, value: 1 },
								{ label: 2, value: 2 },
								{ label: 3, value: 3 },
								{ label: 4, value: 4 },
							]}
							onChange={(nextValue) => setAttributes({ columns_sm: nextValue })}
						/>				
					</div>										
				</PanelBody>
				<PanelBody title={__("Filter styles", "husky-filter-block")}  initialOpen={ false }>
				<RangeControl
					label={__("Padding px", "husky-filter-block")}
					value={ padding }
					onChange={(nextValue) => setAttributes({ padding: nextValue })}
					min={ 0 }
					max={ 100 }
				/>
				<RangeControl
					label={__("Max height of element px", "husky-filter-block")}
					value={ max_height }
					onChange={(nextValue) => setAttributes({ max_height: nextValue })}
					min={ 0 }
					max={ 300 }
					step={ 50 }
				/>	
				</PanelBody>
	
			</InspectorControls>
		</div>
	);
}

