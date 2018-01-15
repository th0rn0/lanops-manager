import React, { PropTypes } from 'react'
import {FormattedDate} from 'react-intl'
import ReactMarkdown  from 'react-markdown'

class NewsItem extends React.Component {
  render() {
    const {title,article,created_at} = this.props.item;
    return <div className="newsitem">
             <h3 className="newsitemtitle">{title}</h3>
             <div className="newsitemtext"><ReactMarkdown source={article} /></div>
             <div className="newsitemdate">

              { created_at &&  <FormattedDate
                     value={created_at}
                     day="numeric"
                     month="long"
                     year="numeric" />
                 }
               </div>
          </div>
  }
}


NewsItem.propTypes = {
   item: PropTypes.object.isRequired
}

export default NewsItem;
