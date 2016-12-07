export const ping = store => next => action => {
    /*
     = function(store) {
     return function(next) {
     return function(action) {
     */
    console.log(`Action: ${action.type}, payload: ${action.payload}`, 'state', store.getState());
    return next(action)
};
