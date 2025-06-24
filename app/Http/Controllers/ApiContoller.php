<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Role;
use App\Models\SlotsCategory;
use App\Models\Train;
use App\Models\UserCategoryTrain;
use App\Models\VideoSlot;
use http\Env\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Word;
use App\Models\Option;
use App\Models\Evaluations;
use App\Models\UserWord;
use App\Models\Bug;
use App\Models\Levels;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Password;
use Intervention\Image\ImageManagerStatic as Image;

class ApiContoller extends Controller
{
    private function generate_ids($user)
    {

        $max_words = (int)Option::select('value')->where('user_id', $user->id)->where('name', 'max_words')->first()->value;
        $new_percentage = (int)Option::select('value')->where('user_id', $user->id)->where('name', 'new_percentage')->first()->value;
        $favourite_percentage = (int)Option::select('value')->where('user_id', $user->id)->where('name', 'favourite_percentage')->first()->value;
        $important_percentage = (int)Option::select('value')->where('user_id', $user->id)->where('name', 'important_percentage')->first()->value;
        $top_percentage = (int)Option::select('value')->where('user_id', $user->id)->where('name', 'top_percentage')->first()->value;
        $low_percentage = (int)Option::select('value')->where('user_id', $user->id)->where('name', 'low_percentage')->first()->value;

        $new_words = floor($max_words * ($new_percentage / 100));
        $favourite_words = floor($max_words * ($favourite_percentage / 100));
        $important_words = floor($max_words * ($important_percentage / 100));
        $top_words = floor($max_words * ($top_percentage / 100));
        $low_words = floor($max_words * ($low_percentage / 100));
        $all_ids = array();
        $user = Auth::user();


        $new_ids = Word::inRandomOrder()
            ->take($new_words)
            ->pluck('id')
            ->toArray();

        $all_ids = array_merge($all_ids, $new_ids);
        $favourite_ids = Word::rightJoin('favourites', 'favourites.word_id', '=', 'words.id')
            ->whereNotIn('words.id', $all_ids)
            ->where('favourites.user_id', $user->id)
            ->take($favourite_words)
            ->pluck('words.id')
            ->toArray();
        $all_ids = array_merge($all_ids, $favourite_ids);
        $important_ids = Word::rightJoin('importants', 'importants.word_id', '=', 'words.id')
            ->whereNotIn('words.id', $all_ids)
            ->where('importants.user_id', $user->id)
            ->take($important_words)
            ->pluck('words.id')
            ->toArray();
        $all_ids = array_merge($all_ids, $important_ids);

        $top_ids = DB::table('evaluations')
            ->whereNotIn('word_id', $all_ids)
            ->where('user_id', $user->id)
            ->groupBy('word_id')
            ->orderBy(\DB::raw('count(word_id)'), 'DESC')
            ->take($top_words)
            ->pluck('word_id')
            ->toArray();
        $all_ids = array_merge($all_ids, $important_ids);

        $low_ids = DB::table('evaluations')
            ->whereNotIn('word_id', $all_ids)
            ->where('user_id', $user->id)
            ->groupBy('word_id')
            ->orderBy(\DB::raw('count(word_id)'), 'ASC')
            ->take($low_words)
            ->pluck('word_id')
            ->toArray();
        $all_ids = array_merge($all_ids, $important_ids);

        $all_ids_count = count($all_ids);
        if ($all_ids_count < $max_words) {
            $rest_ids = $max_words - $all_ids_count;
            $new_ids = Word::inRandomOrder()
                ->take($rest_ids)
                ->pluck('id')
                ->toArray();
            $all_ids = array_merge($all_ids, $new_ids);
        }
        return $all_ids;
    }

    public function forget_password_api(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        Password::sendResetLink($request->only('email'));
        return response()->json(["msg" => 'Reset password link sent on your email id.']);
    }

    public function reset_password_api(Request $request)
    {

        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed']);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        // $credentials = request()->validate([
        // 'email' => 'required|email',
        // 'token' => 'required|string',
        // 'password' => 'required|string|confirmed'
        // ]);

        $reset_password_status = Password::reset($request->only(['email', 'token', 'password', 'password_confirmation' ]), function ($user, $password) {
            $user->password = $password;
            $user->save();
        });

        if ($reset_password_status == Password::PASSWORD_RESET) {
            return response()->json(["success" => "Password has been successfully changed"]);
        }
        if ($reset_password_status == Password::INVALID_PASSWORD) {
            return response()->json(["error" => "كلمة مرور ضعيفة من فضلك استخدم كلمة مرور اقوى"]);
        }
        return response()->json(["error" => "حدث خطأ اثناء تحديث كلمة المرور"]);

    }

    public function today_api(Request $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();

        $last_revision = Option::where('name', 'last_revision')->where('user_id', $user->id)->get();
        if ($last_revision->isEmpty()) {
            $all_ids = $this->generate_ids($user);
            $str_all_ids = implode(",", $all_ids);
            Option::insert(
                ['name' => 'revision_ids', 'value' => $str_all_ids, 'user_id' => $user->id]
            );

            $last_revision = Carbon::now()->toDateTimeString();
            Option::insert(
                ['name' => 'last_revision', 'value' => $last_revision, 'user_id' => $user->id]
            );

        } else {
            $last_revision = Carbon::parse($last_revision->first()->value);
            if (!$last_revision->isToday()) {
                $all_ids = $this->generate_ids($user);
                $str_all_ids = implode(",", $all_ids);

                Option::updateOrCreate(
                    ['name' => 'revision_ids', 'user_id' => $user->id], ['value' => $str_all_ids]
                );

                $last_revision = Carbon::now()->toDateTimeString();
                Option::updateOrCreate(
                    ['name' => 'last_revision', 'user_id' => $user->id], ['value' => $last_revision]
                );
            } else {
                $last_revision = DB::table('options')->where('name', 'revision_ids')
                    ->where('user_id', $user->id)
                    ->get()
                    ->first()
                    ->value;
                $all_ids = array_map('intval', explode(',', $last_revision));
            }
        }
        $words = Word::whereIn('id', $all_ids)
            ->get();

        return response()->json($words, 200);
    }

    public function refresh_todaywords($user)
    {
        $all_ids = $this->generate_ids($user);
        $str_all_ids = implode(",", $all_ids);

        Option::updateOrCreate(
            ['name' => 'revision_ids', 'user_id' => $user->id], ['value' => $str_all_ids]
        );

        $last_revision = Carbon::now()->toDateTimeString();
        Option::updateOrCreate(
            ['name' => 'last_revision', 'user_id' => $user->id], ['value' => $last_revision]
        );
        return true;
    }

    public function mywords_api(Request $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if($user){
            $words = $user->user_words()->get();
            return response()->json($words, 200);
        }
        return response()->json(['error' => 'Unauthorized'], 401);

    }

    public function category_words_api(Request $request, $id)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $words = Word::where('category_id', $id)->orderBy('index')->get();
        return response()->json($words, 200);
    }

    public function api_login(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function username(Request $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();
        $username = $user->name;
        return response()->json(['username' => $username], 200);
    }

    public function user_level_api(Request $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if($user){
            return response()->json(['points'=>$user->points, 'level'=>$user->level]);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function user_points_plus_api(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'points' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if($user){
            $user->points = $user->points + $request->points;
            $user->save();
            if ($request->id && $request->type == 'category_train' ){
                $success_rate = $request->success_rate;
                if ($success_rate > 0.8){
                    $category = Category::find($request->id);
                    $user_category_train = new UserCategoryTrain;
                    $user_category_train->category()->associate($category);
                    $user_category_train->user()->associate($user);
                    $user_category_train->save();
                }
            }
            return response()->json(['points'=>$user->points, 'level'=>$user->level]);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function api_register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        $user->roles()->attach(Role::find(2));

        $max_words = Option::updateOrCreate(
            ['name' => 'max_words', 'user_id' => $user->id],
            ['value' => 20]
        );
        $max_words->save();

        $new_percentage = Option::updateOrCreate(
            ['name' => 'new_percentage', 'user_id' => $user->id],
            ['value' => 20]
        );
        $new_percentage->save();

        $favourite_percentage = Option::updateOrCreate(
            ['name' => 'favourite_percentage', 'user_id' => $user->id],
            ['value' => 20]
        );
        $favourite_percentage->save();


        $important_percentage = Option::updateOrCreate(
            ['name' => 'important_percentage', 'user_id' => $user->id],
            ['value' => 20]
        );
        $important_percentage->save();


        $top_percentage = Option::updateOrCreate(
            ['name' => 'top_percentage', 'user_id' => $user->id],
            ['value' => 20]
        );
        $top_percentage->save();

        $low_percentage = Option::updateOrCreate(
            ['name' => 'low_percentage', 'user_id' => $user->id],
            ['value' => 20]
        );
        $low_percentage->save();


        $credentials = request(['email', 'password']);
        $token = auth('api')->attempt($credentials);

        return $this->respondWithToken($token);
    }

    public function handle_user_social_api(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'data' => 'required',
            'provider' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $data = $request->data;
        $provider= $request->provider;
        $data = json_decode($data, true);
        $user = User::where(['email'=>$data['email']])->first();

        if (!$user) {
            $user = $this->create_user_social($data, $provider);
            if(!$user){
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }
        $user->social_token = $data['token'];
        $user->save();
        return $this->login_user($user);
    }

    public function login_user(User $user)
    {
        JWTAuth::fromUser($user);
        $token = JWTAuth::fromUser($user);
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function create_user_social($data, string $provider)
    {
        try {
            if (!empty($data['photo'])) {
                $image = file_get_contents($data['photo']);
                $unique_name = time().'.jpg';
                file_put_contents(public_path('images/users/'.$unique_name), $image);
                $base_path = public_path('images/users/');
                $image_path = $base_path.$unique_name;
                Image::make($image_path)
                    ->resize(100, 100)
                    ->save($image_path);
            }
            $user = new User;
            $user->name   = $data['name'];
            $user->email  = $data['email'];
            $user->social_provider = $provider;
            $user->social_id = $data['id'];
            $user->social_token = $data['token'];
            if (!empty($data['photo'])) {
                $user->avatar_image = $unique_name;
            }
            $user->save();
            $user->roles()->attach(Role::find(2));


            return $user;
        } catch (Exception $e) {
            return false;
        }
    }

    public function user_info_api(Request $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();

        if($user){
            return response()->json(['display_name'=>$user->name,
                                    'email'=>$user->email,
                                    'join_date'=>$user->created_at,
                                    'avatar_image'=>$user->avatar_image,
                                    ]);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function full_user_info_api(Request $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();

        if($user){
            return response()->json(['display_name'=>$user->name,
                'email'=>$user->email,
                'join_date'=>$user->created_at,
                'points'=>$user->points,
                'level'=>$user->level,
                'avatar_image'=>$user->avatar_image,
            ]);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function update_user_info_api(Request $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'avatar_image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = JWTAuth::parseToken()->authenticate();
        if (!empty($request->avatar_image)) {
            if($user->avatar_image &&
                file_exists(public_path('images/users/') . $user->avatar_image)){
                unlink(public_path('images/users/') . $user->avatar_image);
            }
            $original_img = $request->file('avatar_image');
            $base_path = public_path('images/users/');
            $image_name = time().$original_img->getClientOriginalName();
            $image_path = $base_path.$image_name;
            Image::make($original_img->getRealPath())
                ->resize(100, 100)
                ->save($image_path);
        }
        if($user){
            $user->name = $request->name;
            $user->email = $request->email;
            if (!empty($request->avatar_image)) {
                $user->avatar_image = $image_name;
            }
            $user->save();
            return response()->json(['success'=> 'successfully update user info']);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function categories_api(Request $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();
        $categories = Category::orderBy('index')->get()->map(function ($category) use ($user) {
            $category->completed_by_user = $category->userCompletedCategory($user->id);
            return $category;
        });
        return response()->json($categories, 200);
    }

    public function favourite_api(Request $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();
        $words = $user->favourites()
            ->get();

        return response()->json($words, 200);
    }

    public function important_api(Request $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();
        $words = $user->importants()
            ->get();

        return response()->json($words, 200);
    }

    public function delete_account_api( Request $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if($user){
            $user->delete();
            return response()->json(['success'=> 'successfully delete user'], 200);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function new_word_api(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'german' => 'required',
            'arabic' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if($request->word_id){
            $word = UserWord::find($request->word_id);
            if(!$word){
                return response()->json(['error' => 'This word was not found']);
            }
            $msg = "Word was updated";
        }else{
            $word = new UserWord;
            $msg = "Word was added";
        }
        $word->user_id = $user->id;
        $word->german = $request->german;
        $word->arabic = $request->arabic;
        $word->note = $request->note;
        $word->save();
        return response()->json(array('msg' => $msg), 200);
    }

    public function trash_word_api(Request $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        JWTAuth::parseToken()->authenticate();

        $word = UserWord::find($request->id);
        $word->delete();

        $msg = "Word was added";
        return response()->json(array('msg' => $msg), 200);
    }

    public function set_favourite_api(Request $request)
    {
        $is_favourite = $this->get_cpl($request->val);
        $word_id = $request->id;
        $word = Word::find($word_id);

        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();

        if ($is_favourite == 1) {
            $user->favourites()->attach($word);
            $msg = 'Word added to favourite';
        } else {
            $user->favourites()->detach($word);
            $msg = 'Word remove from favourite';
        }
        return response()->json(array('msg' => ''), 200);
    }

    public function set_important_api(Request $request)
    {
        $is_important = $this->get_cpl($request->val);
        $word_id = $request->id;
        $word = Word::find($word_id);
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if ($is_important == 1) {
            $user->importants()->attach($word);
            $msg = 'Word added to important';
        } else {
            $user->importants()->detach($word);
            $msg = 'Word remove from important';
        }
        return response()->json(array('msg' => $msg), 200);
    }

    public function get_settings_api(Request $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();
        $options = Option::where('user_id', $user->id)->get()->pluck('value', 'name')->all();
        return response()->json($options, 200);

    }

    public function settings_api(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'max_words' => 'required|numeric',
            'new_percentage' => 'required|numeric',
            'favourite_percentage' => 'required|numeric',
            'important_percentage' => 'required|numeric',
            'top_percentage' => 'required|numeric',
            'low_percentage' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();

        $max_words = Option::updateOrCreate(
            ['name' => 'max_words', 'user_id' => $user->id],
            ['value' => $request->max_words]
        );
        $max_words->save();

        $new_percentage = Option::updateOrCreate(
            ['name' => 'new_percentage', 'user_id' => $user->id],
            ['value' => $request->new_percentage]
        );
        $new_percentage->save();

        $favourite_percentage = Option::updateOrCreate(
            ['name' => 'favourite_percentage', 'user_id' => $user->id],
            ['value' => $request->favourite_percentage]
        );
        $favourite_percentage->save();


        $important_percentage = Option::updateOrCreate(
            ['name' => 'important_percentage', 'user_id' => $user->id],
            ['value' => $request->important_percentage]
        );
        $important_percentage->save();


        $top_percentage = Option::updateOrCreate(
            ['name' => 'top_percentage', 'user_id' => $user->id],
            ['value' => $request->top_percentage]
        );
        $top_percentage->save();

        $low_percentage = Option::updateOrCreate(
            ['name' => 'low_percentage', 'user_id' => $user->id],
            ['value' => $request->low_percentage]
        );
        $low_percentage->save();
        $this->refresh_todaywords($user);

        $msg = 'Updated successfully';
        return response()->json(array('msg' => $msg), 200);
    }

    public function dictionary_count_api(Request $request)
    {
        $count = Word::count();
        $limit_pages = round($count / 50);
        return response()->json(array('limit_pages' => $limit_pages), 200);
    }

    public function report_bug_api(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'model_type' => 'required',
            'model_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = JWTAuth::parseToken()->authenticate();
        $bug = new Bug;
        $bug->model_type = $request->model_type;
        $bug->model_id = $request->model_id;
        $bug->user_id = $user->id;
        $bug->text = $request->text;
        $bug->save();
        return response()->json(array('msg' => 'successfully added'), 200);
    }

    public function dictionary_api(Request $request)
    {
        $token = $request->bearerToken();
        $page = $request->page;
        $words_per_page = 50;
        $end_range = $words_per_page * $page;
        $start_range = $end_range - 50;
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        JWTAuth::parseToken()->authenticate();

        $words = Word::where('id', '>', $start_range)->where('id', '<=', $end_range)->get();
        return response()->json($words, 200);
    }

    public function find_word_api(Request  $request)
    {
        $token = $request->bearerToken();
        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        JWTAuth::parseToken()->authenticate();
        $keyword = $request->keyword;
        if(empty($keyword)){
            return response()->json(['error' => 'Empty keyword'], 200);
        }
        $lang = $request->lang;
        if($lang == 'de'){
            $keywords = json_decode( str_replace('?', '', utf8_decode($keyword)));
            // if more than one keyword
            if(count($keywords) > 1){
                $articles = ['der', 'dem', 'den',  'die', 'das', 'ein', 'einem', 'einer', 'eine'];
                $conjunctions = ['und', 'aber', 'denn', 'oder', 'sondern', 'beziehungsweise', 'doch', 'jedoch', 'allein'];
                $prepositions = ['bis', 'durch', 'entlang', 'für', 'gegen', 'ohne','um',
                                'aus', 'bei', 'mit', 'nach', 'seit', 'von', 'zu',
                                'an', 'auf', 'hinter', 'in', 'neben'];

                $all_removes = array_merge($articles, $conjunctions, $prepositions);
                // ignore these words
                foreach ($keywords as $pos_remove=>$keyword_slot){
                    if(array_search($keyword_slot, $all_removes)) {
                        unset($keywords[$pos_remove]);
                    }
                }
                // build query for rest words
                $words_query = Word::query();
                foreach ($keywords as $keyword_slot){
                    $words_query->orWhere('german', 'like', '%' . $keyword_slot . '%')->limit(20)->get();
                }
                return response()->json($words_query->get(), 200);
            }

            $words = Word::where('german', 'like', '%' . $keywords[0] . '%')->limit(20)->get();
            return response()->json($words, 200);
        }elseif ($lang == 'ar'){
            $keywords = json_decode( $keyword);
            $words_query = Word::query();
            foreach ( $keywords as $keyword_slot){
                if($keyword_slot!='الله'){
                    $keyword_slot = str_replace('ة', '', $keyword_slot);
                    $keyword_slot = str_replace('ه', '', $keyword_slot);
                }
                $words_query->orWhere('arabic', 'like', '%' . $keyword_slot . '%')->limit(20)->get();
            }
            $words = $words_query->get();
            return response()->json($words, 200);
        }
        return response()->json(['error' => 'Unknown language'], 200);
    }

    public function quick_search_api(Request $request)
    {
        $token = $request->bearerToken();

        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        JWTAuth::parseToken()->authenticate();

        $keyword = $request->keyword;
        $words = Word::where('german', 'like', '%' . $keyword . '%')
            ->orWhere('arabic', 'like', '%' . $keyword . '%')
            ->orWhere('english', 'like', '%' . $keyword . '%')
            ->orWhere('note', 'like', '%' . $keyword . '%')
            ->limit(20)
            ->get();

        return response()->json($words, 200);
    }

    private function generate_answers($train, $revers_lang = false)
    {
        $lang = $revers_lang?'arabic':'german';
        // predefined answers
        if (!empty($train->choices_order) and !$train->is_choices_random) {
            $answers = array();
            $answers_ids = explode(',', $train->choices_order);
            $flat_answers = Word::select('german')
                            ->whereIn('id', $answers_ids)
                            ->orderByRaw(DB::raw("FIELD(id, $train->choices_order)"))
                            ->pluck($lang);

        } else {
            //random answers
            $answers = array();
            $flat_answers = Word::where('category_id', '=', $train->word->category->id)
                ->where('id', '!=', $train->word->id)
                ->inRandomOrder()
                ->limit(3)
                ->pluck($lang);
            $right_answer = $revers_lang?collect($train->word->arabic):collect($train->word->german);
            $flat_answers = $flat_answers->concat($right_answer);
            $flat_answers = $flat_answers->shuffle();
        }
        $right_answer = '';
        foreach ($flat_answers as $index => $text) {
            $answer = ['index' => $index, 'text' => $text];
            array_push($answers, $answer);
            // right answer
            if ($text == $train->word->$lang) {
                $right_answer = $index;
            }
        }
        return array( $answers, $right_answer);
    }

    private function clean_note(String $note_str)
    {
        $notes = preg_split('(\d+\) )', $note_str, -1, PREG_SPLIT_NO_EMPTY);
        $notes = array_map('trim', $notes);
        return $notes;
    }

    private function generate_train($trains)
    {
        $trains_casted = array();
        foreach ($trains as $train) {
            $train_dict = array();
            switch ($train->type) {
                case 2: // choice
                    $train_dict['id'] = $train->id;
                    $train_dict['type'] = $train->type;
                    if ($train->question == '') {
                        $notes = $this->clean_note($train->word->note);
                        $notes = array_filter($notes, function ($item) use ($train) {
                            return str_contains($item, ' ' . $train->word->german . ' ');
                        });
                        if (empty($notes)) {
                            continue 2;
                        }
                        $note = $notes[array_rand([$notes])];
                        $question = str_replace(' ' . $train->word->german . ' ', ' % ', $note);
                        $train_dict['question'] = $question;
                    } else {
                        $train_dict['question'] = $train->question;
                    }
                    $generate_answer = $this->generate_answers($train);
                    $train_dict['answers'] = $generate_answer[0];
                    $train_dict['rightAnswer'] = [$generate_answer[1]];
                    break;
                case 3: // image
                    $train_dict['id'] = $train->id;
                    $train_dict['type'] = $train->type;
                    $train_dict['question'] = 'ماذا فى الصورة؟';
                    $train_dict['image'] = $train->word->image;
                    $generate_answer = $this->generate_answers($train);
                    $train_dict['answers'] = $generate_answer[0];
                    $train_dict['rightAnswer'] = [$generate_answer[1]];
                    break;
                case 4: // german word meaning
                    $train_dict['id'] = $train->id;
                    $train_dict['type'] = $train->type;
                    $train_dict['question'] = 'ما معنى ('. $train->word->german.') ؟';
                    $generate_answer = $this->generate_answers($train, true);
                    $train_dict['answers'] = $generate_answer[0];
                    $train_dict['rightAnswer'] = [$generate_answer[1]];
                    break;
                case 5: // arabic word meaning
                    $train_dict['id'] = $train->id;
                    $train_dict['type'] = $train->type;
                    $train_dict['question'] = 'ما معنى ('. $train->word->arabic . ') ؟' ;
                    $generate_answer = $this->generate_answers($train);
                    $train_dict['answers'] = $generate_answer[0];
                    $train_dict['rightAnswer'] = [$generate_answer[1]];
                    break;
                case 6: // order of sentence
                    $train_dict['id'] = $train->id;
                    $train_dict['type'] = $train->type;
                    $notes = $this->clean_note($train->word->note);
                    $note = $notes[array_rand([$notes])];
                    $words = explode(' ', $note);
                    $original_order = array();
                    foreach ($words as $index=>$text) {
                        $answer = ['original_index' => $index, 'text' => $text];
                        array_push($original_order, $answer);
                    }
                    shuffle($original_order);
                    $words_dict = array();
                    foreach ($original_order as $index=>$answer){
                        $answer = ['index' => $index, 'original_index'=> $answer['original_index'],'text' => $answer['text']];
                        array_push($words_dict, $answer);
                    }
                    $train_dict['answers'] = $words_dict;

                    usort($words_dict, function ($item1, $item2) {
                        return $item1['original_index'] <=> $item2['original_index'];
                    });
                    $train_dict['rightAnswer'] = array_column($words_dict, 'index');
                    break;
                case 7: // voice
                    $train_dict['id'] = $train->id;
                    $train_dict['type'] = $train->type;
                    $train_dict['question'] = 'ما هى الكلمة التى تسمعها ؟';
                    $train_dict['sound'] = $train->word->sound;
                    $generate_answer = $this->generate_answers($train);
                    $train_dict['answers'] = $generate_answer[0];
                    $train_dict['rightAnswer'] = [$generate_answer[1]];
                    break;
                case 8: // micrphone
                    $train_dict['id'] = $train->id;
                    $train_dict['type'] = $train->type;
                    $train_dict['question'] = $train->word->german;
                    break;
                default: // random
                break;
            }
            array_push($trains_casted, $train_dict);
        }
        $trains_casted = collect($trains_casted);
        return $trains_casted;
    }

    public function category_train_api(Request $request)
    {
        $token = $request->bearerToken();

        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        JWTAuth::parseToken()->authenticate();

        $category_id = $request->category_id;
        $trains = Train::with('Word')->whereHas('Word', function ($query) use ($category_id) {
            return $query->where('words.category_id', '=', $category_id);
        })
        // ->inRandomOrder()->limit(3)
        ->get();
        $trains = $this->generate_train($trains);
        return response()->json($trains, 200);
    }

    public function category_train_completed_api(Request $request)
    {
        $token = $request->bearerToken();

        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $category_id = $request->category_id;
        // if empty category_id return error
        if (empty($category_id) || !isset($category_id)) {
            return response()->json(['error' => 'category_id is required'], 401);
        }
        $category = Category::find($category_id);
        $user = JWTAuth::parseToken()->authenticate();
        $user_category_train = new UserCategoryTrain;
        $user_category_train->category()->associate($category);
        $user_category_train->user()->associate($user);
        $user_category_train->save();

        return response()->json('true', 200);
    }

    public function videos_slots_api (Request $request)
    {
        $token = $request->bearerToken();

        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        JWTAuth::parseToken()->authenticate();

        $validator = \Validator::make($request->all(), [
            'category_id' => 'required',
            'limit_results' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }
        $category_id = $request->category_id;
        $category = SlotsCategory::find($category_id);
        $limit_results = $request->limit_results;
        if($category){
            if($limit_results> 0 ){
                $slots = VideoSlot::where('slots_category_id', '=', $category_id)->limit($limit_results)->with('video')->get();
                return response()->json($slots, 200);
            }
            $slots = VideoSlot::where('slots_category_id', '=', $category_id)->with('video')->get();
            return response()->json($slots, 200);
        }
        return response()->json(['error' => 'No category with this id']);

    }

    public function video_categories_api (Request $request)
    {
        $token = $request->bearerToken();

        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        JWTAuth::parseToken()->authenticate();
        if($request->limit_results){
            return SlotsCategory::take($request->limit_results)->get();
        }
        return SlotsCategory::all();
    }

    public function all_videos_slots_api (Request $request)
    {
        $token = $request->bearerToken();

        if (empty($token) || !isset($token)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        JWTAuth::parseToken()->authenticate();

        $slots =  SlotsCategory::with(['videos_slots'=>function($query){
            $query->with('video');
        }])->get()->toArray();
        $slots_limited = $slots;
        foreach ($slots as $index=>$slot){
            $slots_limited[$index]['videos_slots'] = array_slice($slot['videos_slots'], 0, 4);
        }
        return response()->json($slots_limited, 200);


    }

    public function api_logout(Request $request)
    {
        $token = $request->bearerToken();
        JWTAuth::setToken($token)->invalidate();
        return response()->json('', 200);
    }

    private function get_cpl($val)
    {
        if ($val == 1)
            return 0;
        return 1;
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

}