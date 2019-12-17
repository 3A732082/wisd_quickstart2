<?php

namespace App\Http\Controllers;

//use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\TaskRepository;
use App\Task;
//use Auth;

class TaskController extends Controller
{
    /**
     * 建立一個新的控制器實例。
     *
     * @return void
     */
    /**
     * 任務資源庫的實例。
     *
     * @var TaskRepository
     */
    protected $tasks;

    /**
     * 建立新的控制器實例。
     *
     * @param  TaskRepository  $tasks
     * @return void
     */
    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('auth');

        //$this->tasks = $tasks;  //TaskRepository作用何在???????
    }

    /**
     * 顯示使用者所有任務的清單。
     *
     * @param
     * @return Response
     */
    public function index(Request  $request)
    {
//        $tasks = Task::where('user_id', Auth::user()->id)->get();
//        $tasks = Task::where('user_id', $request->user()->id)->get();
//
//        $user=User::where('id', $request->user()->id);
//        $tasks=$this->tasks->forUser($user);

        //$tasks=auth()->user()->tasks;   // auth()->user()代表登入者的User model
                                        // auth()->user()等同於Auth::user()
        //$tasks=auth()->user()->tasks()->where('id', 2)->get();
        $tasks=auth()->user()->tasks()->paginate(2);  //登入者任務分頁顯示，每頁2筆
        //dd($tasks);

        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * 建立新的任務。
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

//        $request->user()->tasks()->create([
        auth()->user()->tasks()->create(
//            [
//            'name' => $request->name,
//        ]
            $request->all()
        );

        return redirect('/tasks');
    }

    /**
     * 移除給定的任務。
     *
     * @param  Request  $request
     * @param  Task  $task
     * @return Response
     */
    public function destroy(Request $request, Task $task)
    {
        $this->authorize('destroy', $task);

        $task->delete();

        return redirect('/tasks');
    }
}
