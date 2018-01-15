import React from 'react'
import HomePage from './containers/HomePage'
import NoMatchPage from './containers/NoMatchPage'
import AboutUsPage from './containers/AboutUsPage'
import MasterPage from './containers/MasterPage'



import { createRedux } from 'redux';
import { Router, Route, Link, browserHistory, IndexRoute } from 'react-router'

export default class Application extends React.Component {
  render () {
    let {history} = this.props;
    return (
      <Router history={history}>
       <Route path="/" component={MasterPage}>
         <IndexRoute component={HomePage} />
         <Route path="home" component={HomePage}/>
         <Route path="about" component={AboutUsPage}/>
         <Route path="*" component={NoMatchPage}/>
       </Route>
     </Router>
    )
  }
}
