import React from 'react';
import NewsFeed from '../components/newsfeed'
import Hero from '../components/Hero'

class HomePage extends React.Component {
  render() {
    return <div id="homepage">
           <Hero></Hero>
           <div className="herofooter">
             <div className="row">
               <div className="col-xs-6 center">
                 <div className="row">
                   <div className="col-xs-12 center">
                   <h3>What is a LAN Party?</h3>
                    </div>
                    <div className="col-xs-12 center">
                       <i className="ion-chevron-down" />
                   </div>
                 </div>
               </div>
               <div className="col-xs-6 center">
                 <div className="row">
                   <div className="col-xs-12 center">
                    <h3>Newsfeed</h3>
                    </div>
                    <div className="col-xs-12 center">
                       <i className="ion-chevron-down" />
                   </div>
                 </div>
               </div>
             </div>

           </div>
           <div>
              <div className="container">
                <div className="row">
                    <div className="col-xs-6">
                       <h1>New to Lanning?</h1>
                       <p>Are own design entire former get should. Advantages boisterous day excellence boy. Out between our two waiting wishing. Pursuit he he garrets greater towards amiable so placing. Nothing off how norland delight. Abode shy shade she hours forth its use. Up whole of fancy ye quiet do. Justice fortune no to is if winding morning forming.</p>
                       <p>Style never met and those among great. At no or september sportsmen he perfectly happiness attending. Depending listening delivered off new she procuring satisfied sex existence. Person plenty answer to exeter it if. Law use assistance especially resolution cultivated did out sentiments unsatiable. Way necessary had intention happiness but september delighted his curiosity. Furniture furnished or on strangers neglected remainder engrossed. </p>
                    </div>
                    <div className="col-xs-6">
                     <h1>Newsfeed</h1>
                      <NewsFeed ></NewsFeed>
                    </div>
                  </div>
                </div>
              </div>
           </div>
  }
}

export default HomePage;
