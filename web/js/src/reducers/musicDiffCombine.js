import {combineReducers} from 'redux'
import artist from './artist'
import searchForm from './searchForm'
import filterForm from './filterForm'
import globalAlert from './globalAlert'

export default combineReducers({
    artist,
    searchForm,
    filterForm,
    globalAlert
})
