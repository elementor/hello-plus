import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl, Notice, Dashicon } from '@wordpress/components';

export const SettingsPanel = ( { SETTINGS, settingsData, updateSettings } ) => {
	const protocol = window.location.protocol || 'https:';
	const hostname = window.location.hostname || 'example.com';
	const prefix = protocol + '//' + hostname;

	return (
		<PanelBody title={ __( 'Hello Plus Settings', 'hello-plus' ) } >

			<Notice status="warning" isDismissible="false">
				<Dashicon icon="flag" />
				{ __( 'Be cautious, disabling some of the following options may break your website.', 'hello-plus' ) }
			</Notice>

			<ToggleControl
				label={ __( 'Disable description meta tag', 'hello-plus' ) }
				help={ __( 'Remove the description meta tag in singular content pages that contain an excerpt.', 'hello-plus' ) }
				checked={ !! settingsData[ SETTINGS.DESCRIPTION_META_TAG ] || false }
				onChange={ ( value ) => updateSettings( SETTINGS.DESCRIPTION_META_TAG, value ) }
			/>
			<code className="code-example"> &lt;meta name=&quot;description&quot; content=&quot;...&quot; /&gt; </code>

			<ToggleControl
				label={ __( 'Disable skip link', 'hello-plus' ) }
				help={ __( 'Remove the "Skip to content" link used by screen-readers and users navigating with a keyboard.', 'hello-plus' ) }
				checked={ !! settingsData[ SETTINGS.SKIP_LINK ] || false }
				onChange={ ( value ) => updateSettings( SETTINGS.SKIP_LINK, value ) }
			/>
			<code className="code-example"> &lt;a class=&quot;skip-link screen-reader-text&quot; href=&quot;#content&quot;&gt; Skip to content &lt;/a&gt; </code>

			<ToggleControl
				label={ __( 'Disable cross-site header & footer', 'hello-plus' ) }
				help={ __( 'Remove the header & footer sections from all pages, and their CSS/JS files.', 'hello-plus' ) }
				checked={ !! settingsData[ SETTINGS.HEADER_FOOTER ] || false }
				onChange={ ( value ) => updateSettings( SETTINGS.HEADER_FOOTER, value ) }
			/>
			<code className="code-example"> &lt;header id=&quot;site-header&quot; class=&quot;site-header&quot;&gt; ... &lt;/header&gt; </code>
			<code className="code-example"> &lt;footer id=&quot;site-footer&quot; class=&quot;site-footer&quot;&gt; ... &lt;/footer&gt; </code>

			<ToggleControl
				label={ __( 'Disable page title', 'hello-plus' ) }
				help={ __( 'Remove the section above the content that contains the main heading of the page.', 'hello-plus' ) }
				checked={ !! settingsData[ SETTINGS.PAGE_TITLE ] || false }
				onChange={ ( value ) => updateSettings( SETTINGS.PAGE_TITLE, value ) }
			/>
			<code className="code-example"> &lt;div class=&quot;page-header&quot;&gt; &lt;h1 class=&quot;entry-title&quot;&gt; Post title &lt;/h1&gt; &lt;/div&gt; </code>

			<ToggleControl
				label={ __( 'Unregister Hello style.css', 'hello-plus' ) }
				help={ __( "Disable Hello theme's style.css file which contains CSS reset rules for unified cross-browser view.", 'hello-plus' ) }
				checked={ !! settingsData[ SETTINGS.HELLO_PLUS_STYLE ] || false }
				onChange={ ( value ) => updateSettings( SETTINGS.HELLO_PLUS_STYLE, value ) }
			/>
			<code className="code-example"> &lt;link rel=&quot;stylesheet&quot; href=&quot;{ prefix }/wp-content/themes/hello-plus/style.min.css&quot; /&gt; </code>

			<ToggleControl
				label={ __( 'Unregister Hello Plus theme.css', 'hello-plus' ) }
				help={ __( "Disable Hello Plus theme's theme.css file which contains CSS rules that style WordPress elements.", 'hello-plus' ) }
				checked={ !! settingsData[ SETTINGS.HELLO_PLUS_THEME ] || false }
				onChange={ ( value ) => updateSettings( SETTINGS.HELLO_PLUS_THEME, value ) }
			/>
			<code className="code-example"> &lt;link rel=&quot;stylesheet&quot; href=&quot;{ prefix }/wp-content/themes/hello-plus/theme.min.css&quot; /&gt; </code>

		</PanelBody>
	);
};
