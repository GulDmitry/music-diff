import React from "react";
import ReactDOM from "react-dom";

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
                <TestInput />
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

    readMoreClick(e) {
        e.preventDefault();
        this.setState({visible: true}, () => {
        });
    }

    render() {
        var author = this.props.data.author;
        var text = this.props.data.text;
        var showMore = this.props.data.showMore;
        var visible = this.state.visible;

        return (
            <div className="article">
                <p className="news-author">{author}:</p>
                <p className="news-text">{text}</p>

                <a href="#"
                   onClick={(e) => {
                       this.readMoreClick(e)
                   }}
                   className={'news-read-more ' + (visible ? 'hidden' : '')}
                >
                    Show More
                </a>
                <p className={'news-show-more ' + (visible ? '' : 'hidden')}>{showMore}</p>
            </div>
        )
    }
}

class News extends React.Component {
    constructor() {
        super();
        this.state = {
            counter: 0
        };
    }

    increaseCounter() {
        this.setState({counter: ++this.state.counter})
    }

    static propTypes = {
        data: React.PropTypes.arrayOf(React.PropTypes.shape({
            author: React.PropTypes.string.isRequired,
            text: React.PropTypes.string.isRequired,
            showMore: React.PropTypes.string
        })).isRequired,
    }

    render() {
        var data = this.props.data; // <News data={[...]}
        var counter = this.state.counter;
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
                <p>
                    <strong onClick={(e) => {
                        this.increaseCounter()
                    }}
                    >
                        Simple Clicker: {counter}
                    </strong>
                </p>
                {newsTemplate}
            </div>
        );
    }
}

class TestInput extends React.Component {
    onBtnClickHandler() {
        alert(ReactDOM.findDOMNode(this.refs.myTestInput).value);
    }

    render() {
        // Uncontrolled Components. No state => no rerender.
        return <div>
            <input
                className='test-input'
                defaultValue=''
                placeholder='Type value'
                ref='myTestInput'
            />
            <button onClick={() => {this.onBtnClickHandler()}} ref='alert-button'>Show</button>
        </div>
    }
}

export default App;
