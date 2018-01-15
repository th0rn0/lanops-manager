import { NEWSITEMS_REQUESTED, NEWSITEMS_RECEIVED } from '../ActionTypes'

export default function newsFeedReducer(state = {list: []}, action){
  console.log('itemsReducer was called with state', state, 'and action', action)

   switch (action.type) {
        case NEWSITEMS_RECEIVED:
        return { ...state,
           list : action.items
         }

      /* case NEWSITEMS_REQUESTED:
           return { ...state,
              list : [
                {
                  id: 1,
                  text: 'test'
                },
                {
                  id: 2,
                  text: 'test2'
                }
              ]
           }*/
       default:
           return state;
   }
}
