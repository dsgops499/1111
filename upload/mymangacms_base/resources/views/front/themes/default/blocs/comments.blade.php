<script>
    uploads_path = '{{HelperController::avatarUrl(null)}}';
    root_path = '{{route("front.index")}}';
</script>

<script src="{{asset('js/vendor/angular.min.js')}}"></script> <!-- load angular -->

<!-- ANGULAR -->
<script src="{{asset('js/comment/controllers/mainCtrl.js')}}"></script>
<script src="{{asset('js/comment/services/commentService.js')}}"></script>
<script src="{{asset('js/comment/app.js')}}"></script>

<div class="row comments" ng-app="commentApp" ng-controller="mainController">
    <div class="col-xs-12">
        <div id="comments" style="margin: 30px 0;">
            @if(Sentinel::check())
            <form ng-submit="submitComment()" class="comment-form">
                <div class="form-group">
                    <textarea id="comment" name="comment" ng-model="commentData.comment" rows="5" style="width: 100%"></textarea>
                    <input id="user_id" name="user_id" type="hidden" value="{{Sentinel::check()->id}}"/>
                    <input id="parent_comment" name="parent_comment" type="hidden" value=""/>
                </div>
                <div class="pull-right actions">
                    <button type="submit" class="btn btn-primary btn-sm">
                        {{Lang::get('messages.front.home.add-comment')}}
                    </button>
                    <button class="btn btn-default btn-sm cancel">
                        {{Lang::get('messages.front.home.add-comment-cancel')}}
                    </button>
                </div>
                <div class="clearfix"></div>
            </form>
            @else
            @if(env('ALLOW_SUBSCRIBE', false))
            <div class="alert alert-info" role="alert">
                <a href="{{route('login')}}">
                    <i class="fa fa-sign-in"></i> {{Lang::get('messages.front.home.connect-to-comment')}}
                </a>
            </div>
            @endif
            @endif
        </div>

        <p class="text-center" ng-show="loading"><span class="fa fa-spinner fa-5x fa-spin"></span></p>

        <!-- THE COMMENTS =============================================== -->
        <!-- hide these comments if the loading variable is true -->
        <section class="comments">
            <div ng-hide="loading" ng-repeat="comment in comments.comments">
                <article id="comments_<% comment.id %>" class="comment">
                    <div class="meta">
                        <span ng-if="comment.avatar == 1">
                            <img class="thumbnail" width="64" height="64" ng-src="<%uploads_path%><% comment.user_id %>/avatar.jpg" alt="<% comment.username %>">
                        </span>
                        <span ng-if="comment.avatar != 1">
                            <img class="thumbnail" width="64" height="64" ng-src="{{asset('images/placeholder.png')}}" alt="<% comment.username %>">
                        </span>
                        <span class="author"><% comment.username %></span>
                        <span class="date">
                            <time datetime="<% comment.created_at %>"><% comment.created_at %></time>
                        </span>
                    </div>
                    <div class="content">
                        <p><% comment.comment %></p>
                        <a href="#" class="text-muted pull-right reply"><i class="fa fa-reply"></i> {{Lang::get('messages.front.home.comment-reply')}}</a>
                        <input class="comment_id" type="hidden" value="<% comment.id %>"/>
                    </div>
                </article>
                <div ng-repeat="reply in comments.replies| filter: { parent_comment: comment.id }" class="reply-comment">
                    <article id="comments_<% reply.id %>" class="comment">
                        <div class="meta">
                            <span ng-if="reply.avatar == 1">
                                <img class="thumbnail" width="64" height="64" ng-src="<%uploads_path%><% reply.user_id %>/avatar.jpg" alt="<% reply.username %>">
                            </span>
                            <span ng-if="reply.avatar != 1">
                                <img class="thumbnail" width="64" height="64" ng-src="images/placeholder.png" alt="<% reply.username %>">
                            </span>
                            <span class="author"><% reply.username %></span>
                            <span class="date">
                                <time datetime="<% comment.created_at %>"><% reply.created_at %></time>
                            </span>
                        </div>
                        <div class="content">
                            <p><% reply.comment %></p>
                            <a href="#" class="text-muted pull-right reply"><i class="fa fa-reply"></i> {{Lang::get('messages.front.home.comment-reply')}}</a>
                            <input class="comment_id" type="hidden" value="<% comment.id %>"/>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#parent_comment').val('');
        $('.comments').on('click', '.reply', function (e) {
            e.preventDefault();
            comment_id = $(this).parent('.content').find('.comment_id').val();
            $('#parent_comment').val(comment_id);
            formComment = $('form.comment-form');
            $(this).after(formComment);
        });
        $('form').on('click', '.cancel', function (e) {
            e.preventDefault();
            $('#parent_comment').val('');
            formComment = $('form.comment-form');
            $('#comments').append(formComment);
        });
    });
</script>
