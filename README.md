# diffweb
文字比較サイト

## 実行方法
まず実行にはお使いのコンピュータに以下のものが必要になります。
* Docker
* docker-compose
* node.js
* npm
* composer(Docker上でも動かせますが、Docker for Win, MacではDockerは重いので)

## ビルドする
```
docker-compose build
```

## 起動
プロジェクトディレクトリ(このREADME.mdのあるディレクトリ)で
Dockerが起動します。
```
docker-compose up -d
```

### Linuxをお使いの方へ
Dockerのuidとホストのuidが一致しないことによりPermissionのエラーが発生します。  
それを防止するために環境変数userにuid:gidの形式でホスト側のuidとgidをセットしてください
```
export user=$(id -u):$(id -g)   
```

以下のように起動が完了すれば起動完了です。
```
Creating network "diffweb_default" with the default driver
Creating diffweb_redis ... done
Creating diffweb_mysql ... done
Creating diffweb_php   ... done
Creating diffweb_nginx ... done
~/laravel/diffweb 
```

## envファイルを作成する。
Laravelを設定する.envを作成します。  
機密情報が含まれるため後悔しないでください。  

まず.env.exampleを.envに改名してコピーします。  
本来はDBのパスワードやIPアドレスを設定しますが、docker-composeと設定情報を合わせてあるのでそのままでOKです。

## 初期設定を行う
初期設定を行うためにdiffweb_phpのコンテナに入ります。
```
docker exec -it diffweb_php bash
```
## PHPのパッケージをインストールする
これはホスト側でもDocker側でもOKです。  
(.vendorディレクトリはバインドマウントされている)
```
composer install
```

## Laravel用の秘密鍵を作成します。
これはSessionなどの生成に使われるようです。
```
php artisan key:generate
```

## データベースを作成します。
```
php artisan migrate
```


### テストデータを注入する

diffwebのテストユーザーやDiffなどが作成されます。

！！！！！！世界へ公開する場合は実行しないでください！！！！
```
php artisan db:seed
```

この時作成されるテストユーザー  
ユーザー名: test@test.jp  
パスワード: testtest

他にも作成されます。興味があれば
./database/seeders/DatabaseSeeder.phpを見てください。

## jsのパッケージをインストールする
```
npm install
```

## コンパイルを実行します。
これは開発用です。
```
npm run dev
```

こちらはファイルの変更を監視して変更があれば自動でコンパイルしてくれます。
```
npm run watch
```

## ブラウザを開いてログインしてみましょう。
ポートなどは環境に合わせて変更してください。
http://localhost:8080/login

遷移先のホーム画面は現在未実装なので
http://localhost:8080/diffs/3  
などの適当にアクセスできそうなDiffページを探してアクセスします。  
アクセスできればOKです。