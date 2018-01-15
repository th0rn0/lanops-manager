import { createStore, applyMiddleware, combineReducers, compose } from 'redux'
import effects from 'redux-effects'
import fetch from 'redux-effects-fetch'
import { routerReducer } from 'react-router-redux'
import multi from 'redux-multi'

import newsFeedReducer from './reducers/newsfeed'

export default function(data){
   var reducer = combineReducers({newsfeed: newsFeedReducer, routing: routerReducer});
   var finalCreateStore = compose (
                                   applyMiddleware(multi),
                                   applyMiddleware(effects, fetch),
                                   window.devToolsExtension ? window.devToolsExtension() : f => f
                                  )(createStore);
   var store = finalCreateStore(reducer, data);
   return store;
}
