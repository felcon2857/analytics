"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.lazyLoadState = exports.registerLazyLoadHook = void 0;
var coreservices_1 = require("../common/coreservices");
/**
 * A [[TransitionHookFn]] that performs lazy loading
 *
 * When entering a state "abc" which has a `lazyLoad` function defined:
 * - Invoke the `lazyLoad` function (unless it is already in process)
 *   - Flag the hook function as "in process"
 *   - The function should return a promise (that resolves when lazy loading is complete)
 * - Wait for the promise to settle
 *   - If the promise resolves to a [[LazyLoadResult]], then register those states
 *   - Flag the hook function as "not in process"
 * - If the hook was successful
 *   - Remove the `lazyLoad` function from the state declaration
 * - If all the hooks were successful
 *   - Retry the transition (by returning a TargetState)
 *
 * ```
 * .state('abc', {
 *   component: 'fooComponent',
 *   lazyLoad: () => import('./fooComponent')
 *   });
 * ```
 *
 * See [[StateDeclaration.lazyLoad]]
 */
var lazyLoadHook = function (transition) {
    var router = transition.router;
    function retryTransition() {
        if (transition.originalTransition().options().source !== 'url') {
            // The original transition was not triggered via url sync
            // The lazy state should be loaded now, so re-try the original transition
            var orig = transition.targetState();
            return router.stateService.target(orig.identifier(), orig.params(), orig.options());
        }
        // The original transition was triggered via url sync
        // Run the URL rules and find the best match
        var $url = router.urlService;
        var result = $url.match($url.parts());
        var rule = result && result.rule;
        // If the best match is a state, redirect the transition (instead
        // of calling sync() which supersedes the current transition)
        if (rule && rule.type === 'STATE') {
            var state = rule.state;
            var params = result.match;
            return router.stateService.target(state, params, transition.options());
        }
        // No matching state found, so let .sync() choose the best non-state match/otherwise
        router.urlService.sync();
    }
    var promises = transition
        .entering()
        .filter(function (state) { return !!state.$$state().lazyLoad; })
        .map(function (state) { return lazyLoadState(transition, state); });
    return coreservices_1.services.$q.all(promises).then(retryTransition);
};
exports.registerLazyLoadHook = function (transitionService) {
    return transitionService.onBefore({ entering: function (state) { return !!state.lazyLoad; } }, lazyLoadHook);
};
/**
 * Invokes a state's lazy load function
 *
 * @param transition a Transition context
 * @param state the state to lazy load
 * @returns A promise for the lazy load result
 */
function lazyLoadState(transition, state) {
    var lazyLoadFn = state.$$state().lazyLoad;
    // Store/get the lazy load promise on/from the hookfn so it doesn't get re-invoked
    var promise = lazyLoadFn['_promise'];
    if (!promise) {
        var success = function (result) {
            delete state.lazyLoad;
            delete state.$$state().lazyLoad;
            delete lazyLoadFn['_promise'];
            return result;
        };
        var error = function (err) {
            delete lazyLoadFn['_promise'];
            return coreservices_1.services.$q.reject(err);
        };
        promise = lazyLoadFn['_promise'] = coreservices_1.services.$q
            .when(lazyLoadFn(transition, state))
            .then(updateStateRegistry)
            .then(success, error);
    }
    /** Register any lazy loaded state definitions */
    function updateStateRegistry(result) {
        if (result && Array.isArray(result.states)) {
            result.states.forEach(function (_state) { return transition.router.stateRegistry.register(_state); });
        }
        return result;
    }
    return promise;
}
exports.lazyLoadState = lazyLoadState;
//# sourceMappingURL=lazyLoad.js.map