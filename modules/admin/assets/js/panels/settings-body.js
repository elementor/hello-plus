import { Box, Alert, FormControlLabel, Switch, Typography, Stack } from '@elementor/ui';
import { __ } from '@wordpress/i18n';
import { ActionLinksPanel } from './action-links-panel';

export const SettingsBody = ( { settings, settingsData, updateSettings } ) => {
	const protocol = window.location.protocol || 'https:';
	const hostname = window.location.hostname || 'example.com';
	const prefix = protocol + '//' + hostname;

	return (
		<Stack direction={ 'row' }>
			<Box flex="0 0 70%">
				<Stack>
					<Box>
						<Alert severity="warning">
							{ __( 'Be cautious, disabling some of the following options may break your website.', 'hello-plus' ) }
						</Alert>
						<FormControlLabel
							control={
								<Switch
									checked={ !! settingsData[ settings.DESCRIPTION_META_TAG ] || false }
									onChange={ ( event ) => updateSettings( settings.DESCRIPTION_META_TAG, event.target.checked ) }
								/>
							}
							label={ __( 'Disable description meta tag', 'hello-plus' ) }
						/>
						<Typography component="code" className="code-example">
							&lt;meta name=&quot;description&quot; content=&quot;...&quot; /&gt;
						</Typography>
					</Box>
					<Box>
						<FormControlLabel
							control={
								<Switch
									checked={ !! settingsData[ settings.SKIP_LINK ] || false }
									onChange={ ( event ) => updateSettings( settings.SKIP_LINK, event.target.checked ) }
								/>
							}
							label={ __( 'Disable skip link', 'hello-plus' ) }
						/>
						<Typography component="code" className="code-example">
							&lt;a class=&quot;skip-link screen-reader-text&quot; href=&quot;#content&quot;&gt; Skip to
							content &lt;/a&gt;
						</Typography>
					</Box>
					<Box>
						<FormControlLabel
							control={
								<Switch
									checked={ !! settingsData[ settings.HEADER_FOOTER ] || false }
									onChange={ ( event ) => updateSettings( settings.HEADER_FOOTER, event.target.checked ) }
								/>
							}
							label={ __( 'Disable cross-site header & footer', 'hello-plus' ) }
						/>
						<Typography component="code" className="code-example">
							&lt;header id=&quot;site-header&quot; class=&quot;site-header&quot;&gt; ... &lt;/header&gt;
						</Typography>
						<Typography component="code" className="code-example">
							&lt;footer id=&quot;site-footer&quot; class=&quot;site-footer&quot;&gt; ... &lt;/footer&gt;
						</Typography>
					</Box>
					<Box>
						<FormControlLabel
							control={
								<Switch
									checked={ !! settingsData[ settings.PAGE_TITLE ] || false }
									onChange={ ( event ) => updateSettings( settings.PAGE_TITLE, event.target.checked ) }
								/>
							}
							label={ __( 'Disable page title', 'hello-plus' ) }
						/>
						<Typography component="code" className="code-example">
							&lt;div class=&quot;page-header&quot;&gt; &lt;h1 class=&quot;entry-title&quot;&gt; Post
							title &lt;/h1&gt; &lt;/div&gt;
						</Typography>
					</Box>
					<Box>
						<FormControlLabel
							control={
								<Switch
									checked={ !! settingsData[ settings.HELLO_PLUS_STYLE ] || false }
									onChange={ ( event ) => updateSettings( settings.HELLO_PLUS_STYLE, event.target.checked ) }
								/>
							}
							label={ __( 'Unregister Hello Plus style.css', 'hello-plus' ) }
						/>
						<Typography component="code" className="code-example">
							&lt;link
							rel=&quot;stylesheet&quot; href=&quot;{ prefix }/wp-content/themes/hello-plus/style.min.css&quot; /&gt;
						</Typography></Box>
					<Box>
						<FormControlLabel
							control={
								<Switch
									checked={ !! settingsData[ settings.HELLO_PLUS_THEME ] || false }
									onChange={ ( event ) => updateSettings( settings.HELLO_PLUS_THEME, event.target.checked ) }
								/>
							}
							label={ __( 'Unregister Hello Plus theme.css', 'hello-plus' ) }
						/>
						<Typography component="code" className="code-example">
							&lt;link
							rel=&quot;stylesheet&quot; href=&quot;{ prefix }/wp-content/themes/hello-plus/theme.min.css&quot; /&gt;
						</Typography>
					</Box>
				</Stack>
			</Box>
			<Box flex="0 0 30%">
				<ActionLinksPanel />
			</Box>
		</Stack>
	);
};
