import NewsItem from './newsitem'
import { connect } from 'react-redux'
import { getNewsItems } from '../actions/NewsfeedActions'
import React, { PropTypes } from 'react'

class NewsFeed extends React.Component {


  constructor(props) {
      super(props);
     this.props.getNewsItems();
}

  render() {
    let {list} = this.props.newsfeed;
    //debugger;
    return <ul>
           {
             list.map(item =>
                  <NewsItem key={item.id} item={item} />
             )
           }
           </ul>
  }
}


const mapStateToProps = (state, props) => {
   return {
     newsfeed: state.newsfeed
   };
};

const mapDipatchToProps = (dispatch) => {
  return {
     getNewsItems: ()=>{
       dispatch(getNewsItems());
     }
  }
};

const ConnectedNewsFeed = connect(mapStateToProps, mapDipatchToProps)(NewsFeed);

export default ConnectedNewsFeed;
