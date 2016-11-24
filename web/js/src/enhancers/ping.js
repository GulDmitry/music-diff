export const ping = store => next => action => {
    console.log(`Action: ${action.type}, payload: ${action.payload}`, 'state', store.getState());
    return next(action)
};
