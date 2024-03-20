@servers(['web' => 'tabiblibapi@172.104.143.189'])

@setup
    $repository = 'git@gitlab.com:tabiblib-back/code.tabiblib.backend.monolithique.api.v1.laravel.git';
    $app_dir = '/var/www/tabiblib/api.secure.tabiblibdz.com';
    $releases_dir = $app_dir . '/releases';
    $release_day = date('Y-m-d');
    $release_time = date('H-i-s');
    $releases_daily_dir = $app_dir . '/releases' .'/'. $release_day;
    $new_release_dir = $releases_dir .'/'. $release_day ."/". $release_time;
@endsetup

@story('deploy')
    clone_repository
    install_dependency
    update_symlinks
    operate_actions
    update_current_symlinks
    delete_old_release
@endstory

@task('clone_repository')
    echo 'Cloning repository'
    [ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
    git clone --depth 1 -b main --single-branch {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('install_dependency')
    echo "Install dependency in ({{ $release }})"
    cd {{ $new_release_dir }}
{{--    composer install --prefer-dist --no-ansi --no-interaction --no-progress --no-scripts--}}
    composer install
@endtask

@task('update_symlinks')
    echo "Linking storage directory"
    [ -d {{ $app_dir }}/persistent/storage ] && rm -rf {{ $new_release_dir }}/storage || mv {{ $new_release_dir }}/storage  {{ $app_dir }}/persistent/
    ln -nfs {{ $app_dir }}/persistent/storage {{ $new_release_dir }}/storage

    echo 'Linking .env file'
    ln -nfs {{ $app_dir }}/persistent/.env.prod {{ $new_release_dir }}/.env
@endtask

@task('operate_actions')
    echo "give the correct permission to file & folder"
    sudo chown tabiblibapi:www-data -R {{ $app_dir }}/
    sudo chmod 777 -R {{ $app_dir }}/persistent/storage/
    sudo chmod 644 {{ $app_dir }}/persistent/.env.prod

    echo "make artisan command"
    cd {{ $new_release_dir }}
    php artisan storage:link
    php artisan migrate
    yes
@endtask

@task('update_current_symlinks')
    echo 'Linking current release'
    ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current
@endtask

@task('delete_old_release')
    echo "Save only recent 2 releases in the current day and delete the rest in daly dir"
    cd {{ $releases_daily_dir }}
    ls -dt */ | sort -r | tail -n +3 | xargs rm -rf

    echo "Save only recent 3 releases days and delete the rest"
    cd {{ $releases_dir }}
    ls -dt */ | sort -r | tail -n +5 | xargs rm -rf

    echo "Success Job"
@endtask
