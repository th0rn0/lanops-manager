import React, { PropTypes } from 'react'
import { Link } from 'react-router'

class MasterPage extends React.Component {
  render() {
    return <div id="master" className="row">
           <header className="menu col-xs-12">
             <div>
               <div className="row">
                 <div className="col-xs-6">
                   <div className="menulogo">
                     <img src="img/Lanops_logo_new_small_trans.png" alt="Lanops logo" />
                   </div>
                 </div>
                 <div className="col-xs-6">
                   <div className="menulinks">
                     <Link to={'/home'}><div className="menuitem">Home</div></Link>
                     <Link to={'/events'}><div className="menuitem">Events</div></Link>
                     <Link to={'/about'}><div className="menuitem">About</div></Link>
                   </div>
                 </div>
               </div>
             </div>
           </header>
           {this.props.children}
          </div>
  }
}

export default MasterPage;
