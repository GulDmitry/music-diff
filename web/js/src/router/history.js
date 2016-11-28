import {createHistory, useBasename} from 'history'

export const history = useBasename(createHistory)({
    basename: ENV === 'dev' ? '/app_dev.php' : '/'
});
