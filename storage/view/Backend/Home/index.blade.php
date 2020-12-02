@extends('Backend.Layout.layout')

@section('title', '我的桌面')

@section('css')

@endsection

@section('content')
    <blockquote class="layui-elem-quote">欢迎管理员：{{$userInfo['account']}}</blockquote>
    <div class="layui-collapse" lay-accordion>
        <?php
            $pid = intval(@file_get_contents(config('server.settings.pid_file')));
            $has_shell_exec = function_exists('shell_exec');
            $top = 'top -p '.$pid.' -c -b -n 1';
        ?>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">服务器信息</h2>
            <div class="layui-colla-content {{$has_shell_exec ? 'layui-show' : ''}}">
                <table class="layui-table">
                    <tbody>
                    @if($has_shell_exec)
                        <tr>
                            <td>{{$top}}</td>
                            <td colspan="3">
                                <pre>{!! shell_exec($top) !!}</pre>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td>php 版本</td>
                        <td>{{phpversion()}}</td>
                        <td>swoole 版本</td>
                        <td>{{swoole_version()}}</td>
                    </tr>
                    <tr>
                        <td>pid</td>
                        <td>{{$pid}}</td>
                        <td>cpu num</td>
                        <td>{{swoole_cpu_num()}}</td>
                    </tr>
                    <tr>
                        <td>worker num</td>
                        <td>{{config('server.settings.worker_num')}}</td>
                        <td>task worker num</td>
                        <td>{{empty(config('server.settings.task_worker_num')) ? '未启用task进程' : config('server.settings.task_worker_num')}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">mysql池信息</h2>
            <div class="layui-colla-content">
                <table class="layui-table">
                    <tbody>
                    <?php
                        $databases = array_keys(config('databases', []));
                    ?>
                    @foreach($databases as $database)
                    <tr>
                        <td colspan="2">{{$database}}</td>
                    </tr>
                    <tr>
                        <td>min connections</td>
                        <td>{{config('databases.'.$database.'.pool.min_connections')}}</td>
                    </tr>
                    <tr>
                        <td>max connections</td>
                        <td>{{config('databases.'.$database.'.pool.max_connections')}}</td>
                    </tr>
                    <tr>
                        <td>connect timeout</td>
                        <td>{{config('databases.'.$database.'.pool.connect_timeout')}}</td>
                    </tr>
                    <tr>
                        <td>wait timeout</td>
                        <td>{{config('databases.'.$database.'.pool.wait_timeout')}}</td>
                    </tr>
                    <tr>
                        <td>heartbeat</td>
                        <td>{{config('databases.'.$database.'.pool.heartbeat')}}</td>
                    </tr>
                    <tr>
                        <td>max_idle_time</td>
                        <td>{{config('databases.'.$database.'.pool.max_idle_time')}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="layui-colla-item">
            <h2 class="layui-colla-title">redis池信息</h2>
            <div class="layui-colla-content">
                <table class="layui-table">
                    <tbody>
                    <?php
                    $redisArr = array_keys(config('redis', []));
                    ?>
                    @foreach($redisArr as $redis)
                        <tr>
                            <td colspan="2">{{$redis}}</td>
                        </tr>
                        <tr>
                            <td>min connections</td>
                            <td>{{config('redis.'.$redis.'.pool.min_connections')}}</td>
                        </tr>
                        <tr>
                            <td>max connections</td>
                            <td>{{config('redis.'.$redis.'.pool.max_connections')}}</td>
                        </tr>
                        <tr>
                            <td>connect timeout</td>
                            <td>{{config('redis.'.$redis.'.pool.connect_timeout')}}</td>
                        </tr>
                        <tr>
                            <td>wait timeout</td>
                            <td>{{config('redis.'.$redis.'.pool.wait_timeout')}}</td>
                        </tr>
                        <tr>
                            <td>heartbeat</td>
                            <td>{{config('redis.'.$redis.'.pool.heartbeat')}}</td>
                        </tr>
                        <tr>
                            <td>max_idle_time</td>
                            <td>{{config('redis.'.$redis.'.pool.max_idle_time')}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        //注意：折叠面板 依赖 element 模块，否则无法进行功能性操作
        layui.use('element', function() {
            var element = layui.element;
        });
    </script>
@endsection
