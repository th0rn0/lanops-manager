import NewsItem from './newsitem'
import { connect } from 'react-redux'
import { getNewsItems } from '../actions/NewsfeedActions'
import React, { PropTypes } from 'react'

class Hero extends React.Component {

  constructor() {
    super();
    this.state = this.handleResize();
 }

 handleResize(e) {

  var height = window.innerHeight;
  if(height>200)
  {
     height -=100;
  }

   var newState = {targetWidth: window.innerWidth - 40, targetHeight: height};
   if(e!==undefined)
   {
     this.setState(newState);
   }

   return newState;
}

  componentDidMount() {
   this.boundhandleResize = this.handleResize.bind(this);
   window.addEventListener('resize', this.boundhandleResize);
 }

 componentWillUnmount() {
   window.removeEventListener('resize', this.boundhandleResize);
 }


  render() {
    return <div className="hero" style={{width: this.state.targetWidth, height: this.state.targetHeight}}>
              <div className="row heroinfo" style={{top: this.state.targetHeight/2}}>
<div className="col-xs-1">

</div>
<div className="col-xs-6">

  <div className="row">
        <div className="col-xs-12">
           <h1>LanOps 23: Unstoppable</h1>
        </div>
        <div className="col-xs-2 center">
          <i className="ion-calendar"></i>
        </div>

        <div className="col-xs-10">
          <div className="row">
            <div className="col-xs-12">
              July 22nd 4PM to
            </div>
            <div className="col-xs-12">
              July 24th 4PM 2016
            </div>
          </div>
       </div>

     </div>

     <div className="row">

       <div className="col-xs-2 center">
         <i className="ion-location"></i>
       </div>

       <div className="col-xs-10">
         <div className="row">
           <div className="col-xs-12">
             Dronfield near Sheffield
           </div>
           <div className="col-xs-12">
             <a href="#">Directions</a>
           </div>
         </div>
      </div>

    </div>


    <div className="row">

      <div className="col-xs-2 center">
        <i className="ion-ios-pricetag"></i>
      </div>

      <div className="col-xs-10">
        <div className="row">
          <div className="col-xs-12">
            £30 for the full weekend.
          </div>
        </div>
     </div>

    </div>
</div>
</div>
</div>
  }
}

export default Hero;
