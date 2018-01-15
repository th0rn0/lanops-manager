import React from 'react';
import ReactDOM from 'react-dom'
import { Provider } from 'react-redux'
import { syncHistoryWithStore } from 'react-router-redux'
import { Router, Route, Link, browserHistory } from 'react-router'
import {IntlProvider} from 'react-intl'
import 'json.date-extensions'

import HomePage from './containers/homepage'
import Application from './application'
import createStore from './create-store'

const store = createStore();
const history = syncHistoryWithStore(browserHistory, store)

JSON.useDateParser();

ReactDOM.render(
  <Provider store={ store }>
     <IntlProvider locale="en">
       <Application history={history} />
     </IntlProvider>
  </Provider>,
  document.getElementById('app')
);
