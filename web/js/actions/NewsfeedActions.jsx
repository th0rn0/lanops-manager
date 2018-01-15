import { NEWSITEMS_REQUESTED, NEWSITEMS_RECEIVED } from '../ActionTypes'
import {bind} from 'redux-effects'
import {fetch} from 'redux-effects-fetch'

function receiveNewsItems(items){
  return {
    type: NEWSITEMS_RECEIVED,
    items: items
  }
}

export function getNewsItems(){
   return [{
     type: NEWSITEMS_REQUESTED
   },
   bind(fetch('news', {method: 'GET'}),({value}) => {
        return receiveNewsItems(value);
   },
   (response) => {
      debugger;

   } )
 ]
}
