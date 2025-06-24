<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Comment;
use App\Models\Article;
use Session;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Comment $comments)
    {
        // $comments = Comment::all();
        return view('comment.index', compact('comments'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Article $articles)
    {
        // $articles = Article::all();
        return view('comment.create', compact('articles'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'name'      =>  'required|max:255',
            'email'     =>  'required|email|max:255',
            'comment'   =>  'required|min:5|max:2000'
            ));
        $post = Article::find($request->article_id);
        $comment = new Comment();
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->comment = $request->comment;
        $comment->approved = true;
        $comment->article()->associate($post);
        $comment->save();
        return redirect()->back()->with('success', 'Comment Created');

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin']);
        $comment = Comment::find($id);
        $articles = Article::all();
        return view('comment.edit', compact('comment', 'articles'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'name'      =>  'required|max:255',
            'email'     =>  'required|email|max:255',
            'comment'   =>  'required|min:5|max:2000'
            ));
        $comment = Comment::find($id);
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->comment = $request->comment;
        $comment->approved = $request->approved;
        $comment->save();
        return redirect()->back()->with('success', 'Comment Updated');

    }
    public function delete($id)
    {
        $comment = Comment::find($id);
        return view('comments.delete', compact('comment'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin']);
        $comment = Comment::find($id);
        $post_id = $comment->article->id;
        $comment->delete();
        return redirect()->back()->with('success', 'Comment Deleted');
    }
}
