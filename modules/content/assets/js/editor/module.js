import ControlChooseItemViewImg from './controls/choose-img.js';

export default class ContentModule extends elementorModules.editor.utils.Module {
	onElementorInit() {
		elementor.addControlView( 'choose-img', ControlChooseItemViewImg );
	}
}
