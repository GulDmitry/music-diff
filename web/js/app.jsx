import React from "react";
import ReactDOM from "react-dom";
import {EventEmitter} from "events"; // or Events, {EventEmitter}
// Instead of node `events` API agnostic npm event-emmitter can be used. It has on, off events.

var ee = new EventEmitter();

class App extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            news: props.news
        };
    }

    componentWillReceiveProps(nextProps) {
        this.setState({
            news: nextProps.news
        });
    }

    componentDidMount() {
        ee.on('News.add', function(item) {
            var nextNews = item.concat(this.state.news);
            this.setState({news: nextNews});
        }.bind(this));
    }

    componentWillUnmount() {
        ee.removeListener('News.add');
    }

    render() {
        return (
            <div className="app text-primary">
                <h3> App Component </h3>
                App component, uses News and Comments inside.
                <AddForm />
                <h3> News </h3>
                <News data={this.state.news}/> {/* Comments in JSX are written this way */}
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
                <p className="news-author">{author}: {text}</p>
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

        return <div className="news">
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

    }
}

class AddForm extends React.Component {
    constructor() {
        super();
        // Instead of writing () => {this.onBtnClickHandler} in JSX.
        this.onBtnClickHandler = this.onBtnClickHandler.bind(this);

        this.state = {
            agreeNotChecked: true,
            authorIsEmpty: true,
            textIsEmpty: true
        }
    }

    // See https://facebook.github.io/react/docs/react-component.html#the-component-lifecycle
    // System methods:
    componentDidMount() {
        // Without this.state.
        // ReactDOM.findDOMNode(this.refs.author).focus();
        // ReactDOM.findDOMNode(this.refs.alertButton).disabled = true;
    }

    componentWillReceiveProps(nextProps) {
        // Here calling setState does not call render().
        // this.setState({
        //     likesIncreasing: nextProps.likeCount > this.props.likeCount
        // });
    }

    componentWillUpdate(nextProps, nextState) {
        // Directly before render. props and state are new.
        // setState() is forbidden!
    }

    componentDidUpdate(prevProps, prevState) {
        // After render, the first render is excluded.
    }

    componentWillUnmount() {
        // Before remove from DOM.
    }

    onBtnClickHandler(e) {
        e.preventDefault();
        var textEl = ReactDOM.findDOMNode(this.refs.text);
        var author = ReactDOM.findDOMNode(this.refs.author).value;
        var text = textEl.value;
        var item = [{
            author: author,
            text: text,
            showMore: '...'
        }];
        ee.emit('News.add', item);
        textEl.value = '';
        this.setState({textIsEmpty: true});
    }

    onCheckRuleClick(e) {
        // Without state
        // ReactDOM.findDOMNode(this.refs.alertButton).disabled = !e.target.checked;
        // With state
        this.setState({agreeNotChecked: !this.state.agreeNotChecked});
    }

    onFieldChange(state, e) {
        // this.refs[refName]
        this.setState({[state]: e.target.value.trim().length === 0})
    }

    render() {
        // Form components that do not provide a value prop are uncontrolled.
        // Uncontrolled Components. No state => no rerender.
        return (
            <form>
                <div className="form-group">
                    <label htmlFor="authorInput">Author</label>
                    <input
                        type='text'
                        id="authorInput"
                        className='form-control add-form-author'
                        defaultValue=''
                        placeholder="Author's name"
                        onChange={this.onFieldChange.bind(this, 'authorIsEmpty')}
                        ref='author'
                    />
                </div>
                <div className="form-group">
                    <label htmlFor="textInput">Text</label>
                    <textarea
                        id="textInput"
                        className='form-control add-form-text'
                        defaultValue=''
                        placeholder='News'
                        onChange={this.onFieldChange.bind(this, 'textIsEmpty')}
                        ref='text'
                    />
                </div>
                <div className="checkbox">
                    <label className='add-form-checkrule'>
                        <input
                            type='checkbox'
                            onChange={(e) => {
                                this.onCheckRuleClick(e)
                            }}
                            defaultChecked={false}
                            ref='checkrule'
                        />I agree to show alert
                    </label>
                </div>

                <button
                    type="button"
                    className='btn btn-default add-form-btn'
                    onClick={this.onBtnClickHandler}
                    ref='alertButton'
                    disabled={this.state.agreeNotChecked || this.state.authorIsEmpty || this.state.textIsEmpty}
                > {/* disabled={true} сannot be changed by ReactDOM later */}
                    Add news
                </button>
            </form>
        );
    }
}

export default App;
