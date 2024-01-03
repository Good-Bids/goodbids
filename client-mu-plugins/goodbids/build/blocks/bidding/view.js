/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/blocks/bidding/components/Driver.tsx":
/*!**************************************************!*\
  !*** ./src/blocks/bidding/components/Driver.tsx ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Driver: () => (/* binding */ Driver)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_get_initial_state__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../utils/get-initial-state */ "./src/blocks/bidding/utils/get-initial-state.ts");
/* harmony import */ var _countdown_timer__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./countdown-timer */ "./src/blocks/bidding/components/countdown-timer.tsx");
/* harmony import */ var _metrics__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./metrics */ "./src/blocks/bidding/components/metrics.tsx");





function Driver() {
  const initialState = (0,_utils_get_initial_state__WEBPACK_IMPORTED_MODULE_1__.getInitialState)();
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "w-full text-base"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_metrics__WEBPACK_IMPORTED_MODULE_3__.Metrics, {
    blocks: [{
      type: 'bids',
      value: initialState.bids
    }, {
      type: 'raised',
      value: initialState.raised
    }, {
      type: 'last-bid',
      value: initialState.lastBid
    }]
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_countdown_timer__WEBPACK_IMPORTED_MODULE_2__.CountdownTimer, null));
}

/***/ }),

/***/ "./src/blocks/bidding/components/clock-icon.tsx":
/*!******************************************************!*\
  !*** ./src/blocks/bidding/components/clock-icon.tsx ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ClockIcon: () => (/* binding */ ClockIcon)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);


function ClockIcon() {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    xmlns: "http://www.w3.org/2000/svg",
    height: "24",
    width: "24",
    viewBox: "0 0 24 24"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    fill: "currentColor",
    d: "M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10Zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm1-8h4v2h-6V7h2v5Z"
  }));
}

/***/ }),

/***/ "./src/blocks/bidding/components/countdown-timer.tsx":
/*!***********************************************************!*\
  !*** ./src/blocks/bidding/components/countdown-timer.tsx ***!
  \***********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   CountdownTimer: () => (/* binding */ CountdownTimer)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _clock_icon__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./clock-icon */ "./src/blocks/bidding/components/clock-icon.tsx");
/* harmony import */ var _utils_get_initial_state__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../utils/get-initial-state */ "./src/blocks/bidding/utils/get-initial-state.ts");




const SECONDS_IN_MINUTE = 60;
const SECONDS_IN_HOUR = 60 * SECONDS_IN_MINUTE;
const SECONDS_IN_DAY = 24 * SECONDS_IN_HOUR;
function formatTimeRemaining(timeRemaining) {
  const seconds = Math.floor(timeRemaining / 1000);
  if (seconds > SECONDS_IN_DAY) {
    if (seconds > 2 * SECONDS_IN_DAY) {
      return `${Math.floor(seconds / SECONDS_IN_DAY)} days`;
    }
    return `${Math.floor(seconds / SECONDS_IN_DAY)} day`;
  }
  if (seconds > SECONDS_IN_HOUR) {
    const minutes = Math.floor(seconds % SECONDS_IN_HOUR / SECONDS_IN_MINUTE);
    if (minutes > 0) {
      return `${Math.floor(seconds / SECONDS_IN_HOUR)} hours, ${minutes} minutes`;
    }
    return `${Math.floor(seconds / SECONDS_IN_HOUR)} hours`;
  }
  const remainingSeconds = (seconds % SECONDS_IN_MINUTE).toString().padStart(2, '0');
  return `${Math.floor(seconds / SECONDS_IN_MINUTE)}:${remainingSeconds}`;
}
function getTimeRemaining(user, lastBidder, {
  status,
  timeRemaining
}, userBids) {
  if (status === 'not-started') {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("b", null, "Bidding starts in ", formatTimeRemaining(timeRemaining)));
  }
  if (status === 'in-progress') {
    if (user === lastBidder) {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("b", null, "You will win in ", formatTimeRemaining(timeRemaining)), ' ', "if nobody else bids.");
    }
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("b", null, "Ending in ", formatTimeRemaining(timeRemaining)), " if nobody else bids.");
  }
  if (user === lastBidder) {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("b", null, "Auction has closed."), " Congratulations, you won!");
  }
  if (userBids > 0) {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("b", null, "Auction has closed."), " Sorry, you were out-bid.");
  }
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("b", null, "Auction has closed."));
}
function getCountdownTime(startTimeString, endTimeString) {
  const startTime = new Date(startTimeString).getTime();
  const endTime = new Date(endTimeString).getTime();
  const now = new Date().getTime();
  if (now < startTime) {
    return {
      status: 'not-started',
      timeRemaining: startTime - now
    };
  }
  if (now < endTime) {
    return {
      status: 'in-progress',
      timeRemaining: endTime - now
    };
  }
  return {
    status: 'ended',
    timeRemaining: 0
  };
}
function CountdownTimer() {
  const [timeRemaining, setTimeRemaining] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(getCountdownTime(_utils_get_initial_state__WEBPACK_IMPORTED_MODULE_2__.initialState.startTime, _utils_get_initial_state__WEBPACK_IMPORTED_MODULE_2__.initialState.endTime));
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const interval = setInterval(() => {
      setTimeRemaining(getCountdownTime(_utils_get_initial_state__WEBPACK_IMPORTED_MODULE_2__.initialState.startTime, _utils_get_initial_state__WEBPACK_IMPORTED_MODULE_2__.initialState.endTime));
    }, 1000);
    return () => clearInterval(interval);
  });
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "flex items-center gap-3 px-4"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_clock_icon__WEBPACK_IMPORTED_MODULE_1__.ClockIcon, null), getTimeRemaining('no-user', _utils_get_initial_state__WEBPACK_IMPORTED_MODULE_2__.initialState.lastBidder, timeRemaining, _utils_get_initial_state__WEBPACK_IMPORTED_MODULE_2__.initialState.userBids));
}

/***/ }),

/***/ "./src/blocks/bidding/components/metrics.tsx":
/*!***************************************************!*\
  !*** ./src/blocks/bidding/components/metrics.tsx ***!
  \***************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Metrics: () => (/* binding */ Metrics)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);


const metricTypes = {
  bids: 'BIDS',
  raised: 'RAISED',
  'last-bid': 'LAST BID',
  'winning-bid': 'WINNING BID'
};
function MetricBlock({
  type,
  value
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "flex flex-col text-center"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "m-0 font-thin uppercase has-x-small-font-size"
  }, metricTypes[type]), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "m-1 font-extrabold"
  }, type === 'bids' ? value : `$${value.toLocaleString()}`));
}
function Metrics({
  blocks
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "grid grid-cols-3 gap-5 my-4"
  }, blocks.map(block => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(MetricBlock, {
    key: block.type,
    ...block
  })));
}

/***/ }),

/***/ "./src/blocks/bidding/utils/get-initial-state.ts":
/*!*******************************************************!*\
  !*** ./src/blocks/bidding/utils/get-initial-state.ts ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   getInitialState: () => (/* binding */ getInitialState),
/* harmony export */   initialState: () => (/* binding */ initialState)
/* harmony export */ });
function getInitialState() {
  const gutenblock = document.getElementById('goodbids-bidding');
  console.log(gutenblock?.getAttribute('data-gutenberg-attributes'));
  const root = document.getElementById('bidding-block');
  const auctionId = root?.getAttribute('data-auction-id');
  const initialBids = root?.getAttribute('data-initial-bids');
  const initialRaised = root?.getAttribute('data-initial-raised');
  const initialLastBid = root?.getAttribute('data-initial-last-bid');
  const initialStartTime = root?.getAttribute('data-initial-start-time');
  const initialEndTime = root?.getAttribute('data-initial-end-time');
  const initialFreeBids = root?.getAttribute('data-initial-free-bids');
  const initialUserBids = root?.getAttribute('data-initial-user-bids');
  const initialLastBidder = root?.getAttribute('data-initial-last-bidder');
  return {
    auctionId,
    bids: initialBids ? parseInt(initialBids, 10) : 0,
    raised: initialRaised ? parseInt(initialRaised, 10) : 0,
    lastBid: initialLastBid ? parseInt(initialLastBid, 10) : 0,
    startTime: initialStartTime || '',
    endTime: initialEndTime || '',
    freeBids: initialFreeBids ? parseInt(initialFreeBids, 10) : 0,
    userBids: initialUserBids ? parseInt(initialUserBids, 10) : 0,
    lastBidder: initialLastBidder || ''
  };
}
const initialState = getInitialState();

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

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
/************************************************************************/
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
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!*************************************!*\
  !*** ./src/blocks/bidding/view.tsx ***!
  \*************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _components_Driver__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/Driver */ "./src/blocks/bidding/components/Driver.tsx");




(0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.render)((0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_Driver__WEBPACK_IMPORTED_MODULE_2__.Driver, null), document.getElementById('goodbids-bidding'));
})();

/******/ })()
;
//# sourceMappingURL=view.js.map