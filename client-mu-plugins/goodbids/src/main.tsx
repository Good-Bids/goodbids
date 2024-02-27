import 'htmx.org';
import './assets/css/main.css';
import './assets/js/checkout/checkout.js';
import './assets/js/copy-to-clipboard.js';
import WatchAuction from './assets/js/controllers/watch_auction_controller.js';

import { Application } from '@hotwired/stimulus';

const application = Application.start();

application.register('watch-auction', WatchAuction);
