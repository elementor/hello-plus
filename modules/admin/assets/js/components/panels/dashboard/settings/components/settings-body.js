import { Alert } from '@elementor/ui/Alert';
import { Switch } from '@elementor/ui/Switch';
import { Typography } from '@elementor/ui/Typography';
import { Stack } from '@elementor/ui/Stack';
import { Paper } from '@elementor/ui/Paper';
import { __ } from '@wordpress/i18n';
import { SettingCard } from './setting-card';

export const SettingsBody = ( { settings, settingsData, updateSettings } ) => {
	const protocol = window.location.protocol || 'https:';
	const hostname = window.location.hostname || 'example.com';
	const prefix = protocol + '//' + hostname;

	return (
		<Paper sx={ { p: 2 } }>
			<Stack gap={ 1 }>
				<Alert severity="warning">
					{ __( 'Be cautious, disabling some of the following options may break your website.', 'hello-plus' ) }
				</Alert>
				<SettingCard
					description={ __( 'Remove the description meta tag in singular content pages that contain an excerpt.', 'hello-plus' ) }
					code={ <Typography component="code">
						&lt;meta name=&quot;description&quot; content=&quot;...&quot; /&gt;
					</Typography> }
					label={ __( 'Disable description meta tag', 'hello-plus' ) }
					control={ <Switch
						checked={ !! settingsData[ settings.DESCRIPTION_META_TAG ] || false }
						onChange={ ( event ) => {
							updateSettings( settings.DESCRIPTION_META_TAG, event.target.checked );
						} }
					/> }

				/>
				<SettingCard
					description={ __( 'Remove the "Skip to content" link used by screen-readers and users navigating with a keyboard.', 'hello-plus' ) }
					code={ <Typography component="code">
						&lt;a class=&quot;skip-link screen-reader-text&quot; href=&quot;#content&quot;&gt; Skip to
						content &lt;/a&gt;
					</Typography> }
					label={ __( 'Disable skip link', 'hello-plus' ) }
					control={ <Switch
						checked={ !! settingsData[ settings.SKIP_LINK ] || false }
						onChange={ ( event ) => updateSettings( settings.SKIP_LINK, event.target.checked ) }
					/> }
				/>
				<SettingCard
					label={ __( 'Disable cross-site header & footer', 'hello-plus' ) }
					description={ __( 'Remove the header & footer sections from all pages, and their CSS/JS files.', 'hello-plus' ) }
					code={ <><Typography component="code">
						&lt;header id=&quot;site-header&quot; class=&quot;site-header&quot;&gt; ... &lt;/header&gt;
					</Typography>
						<Typography component="code">
							&lt;footer id=&quot;site-footer&quot; class=&quot;site-footer&quot;&gt; ... &lt;/footer&gt;
						</Typography></> }
					control={ <Switch
						checked={ !! settingsData[ settings.HEADER_FOOTER ] || false }
						onChange={ ( event ) => updateSettings( settings.HEADER_FOOTER, event.target.checked ) }
					/> }
				/>
				<SettingCard
					label={ __( 'Disable page title', 'hello-plus' ) }
					description={ __( 'Remove the section above the content that contains the main heading of the page.', 'hello-plus' ) }
					code={ <Typography component="code">
						&lt;div class=&quot;page-header&quot;&gt; &lt;h1 class=&quot;entry-title&quot;&gt; Post
						title &lt;/h1&gt; &lt;/div&gt;
					</Typography> }
					control={ <Switch
						checked={ !! settingsData[ settings.PAGE_TITLE ] || false }
						onChange={ ( event ) => updateSettings( settings.PAGE_TITLE, event.target.checked ) }
					/> }
				/>
				<SettingCard
					label={ __( 'Unregister Hello Plus style.css', 'hello-plus' ) }
					description={ __( 'Disable Hello Plus\'s style.css file which contains CSS reset rules for unified cross-browser view.', 'hello-plus' ) }
					code={ <Typography component="code">
						&lt;link
						rel=&quot;stylesheet&quot; href=&quot;{ prefix }/wp-content/themes/hello-plus/style.min.css&quot; /&gt;
					</Typography> }
					control={ <Switch
						checked={ !! settingsData[ settings.HELLO_PLUS_STYLE ] || false }
						onChange={ ( event ) => updateSettings( settings.HELLO_PLUS_STYLE, event.target.checked ) }
					/> }
				/>
				<SettingCard
					label={ __( 'Unregister Hello Plus theme.css', 'hello-plus' ) }
					description={ __( 'Disable Hello Plus\'s theme.css file which contains CSS rules that style WordPress elements.', 'hello-plus' ) }
					code={ <Typography component="code">
						&lt;link
						rel=&quot;stylesheet&quot; href=&quot;{ prefix }/wp-content/themes/hello-plus/theme.min.css&quot; /&gt;
					</Typography> }
					control={ <Switch
						checked={ !! settingsData[ settings.HELLO_PLUS_THEME ] || false }
						onChange={ ( event ) => updateSettings( settings.HELLO_PLUS_THEME, event.target.checked ) }
					/> }
				/>
			</Stack>
		</Paper>
	);
};
