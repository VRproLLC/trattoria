<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Новое уведомление</h3>
                </div>
                <form action="" method="post" class="form-horizontal">
                    <div class="box-body">
                        <div class="fields-group">
                            <div class="col-md-12">
                                <div class="form-group  ">
                                    <label for="subject" class="col-sm-2 asterisk control-label">Заголовок</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                            <input type="text" id="subject" name="subject" value=""
                                                   class="form-control name" placeholder="Введення заголовок" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="text" class="col-sm-2 asterisk control-label">Текст</label>
                                    <div class="col-sm-8">
                                        <textarea name="text" id="text" class="form-control http_path" rows="5"
                                                  placeholder="Введите текст уведомления" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group  ">
                                    <label for="link" class="col-sm-2  control-label">Ссылка</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                            <input type="text" id="link" name="link" value="" class="form-control slug"
                                                   placeholder="Введите ссылку">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group  ">
                                    <label for="send_date" class="col-sm-2  control-label">Время отправки</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                            <input type="datetime-local" name="send_date" id="send_date"
                                                   class="form-control" placeholder="Дата с"
                                                   value="{{ \Carbon\Carbon::now() }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <div class="btn-group pull-right">
                                <button type="submit" class="btn btn-primary">Создать</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
