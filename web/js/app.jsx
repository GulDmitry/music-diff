import React from "react";

var news = [
    {
        author: 'Author 1',
        text: 'Text 1',
        showMore: 'Show more text 1'
    },
    {
        author: 'Author 2',
        text: 'Text 2',
        showMore: 'Show more text 2'
    },
    {
        author: 'Author 3',
        text: 'Text 3',
        showMore: 'Show more text 3'
    }
];

class App extends React.Component {
    render() {
        return (
            <div className="app text-primary">
                <h3> App Component </h3>
                App component, uses News and Comments inside.
                <h3> News </h3>
                <News data={news}/> {/* Comments in JSX are written this way */}
            </div>
        );
    }
}

class Article extends React.Component {
    constructor() {
        super();
        this.state = {
            visible: false
        };
    }

    render() {
        var author = this.props.data.author;
        var text = this.props.data.text;
        var showMore = this.props.data.showMore;

        return (
            <div className="article">
                <p className="news-author">{author}:</p>
                <p className="news-text">{text}</p>
                <a href="#" className='news_read-more'>More</a>
                <p className="news-show-more">{showMore}</p>
            </div>
        )
    }
}

class News extends React.Component {
    static propTypes = {
        data: React.PropTypes.arrayOf(React.PropTypes.shape({
            author: React.PropTypes.string.isRequired,
            text: React.PropTypes.string.isRequired,
            showMore: React.PropTypes.string
        })).isRequired,
    }

    render() {
        var data = this.props.data; // <News data={[...]}
        var newsTemplate;

        if (data.length) {
            // Better not to show environment if no data available.
            newsTemplate = data.map(function(item, index) {
                return (
                    <div key={index}> {/* Should be unique withing whole DOM. lodash.uniqueid for example. */}
                        <Article data={item}/>
                    </div>
                );
            })
        } else {
            newsTemplate = <p>No News.</p>
        }

        return (
            <div className="news">
                <strong className={data.length ? '' : 'hidden'}>News: {data.length}</strong>
                {newsTemplate}
            </div>
        );
    }
}

export default App;
