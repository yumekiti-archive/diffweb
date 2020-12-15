
## 概要
文字列を２つ入れ比較しそれを同期&共有するサービスを作成する。

## 仕様


- 認証方法
  - ユーザはEmailとパスワードで認証することができる。
  - 認証情報はSessionによって保持される。

- Diffについて
  - ユーザーは2つの文章を比較することができる
  - この2つの文章を比較する仕組みをDiffと呼ぶこととする。
  - このDiffは他のユーザー(複数)と共有することができる。
  - Diffは推測不能なURLによって共有することができる。
  - Diffはユーザーを指定することによって共有することができる。
  - ユーザーはDiffを複数作成することができる。
  - ユーザーはDiffを永続化することができる。
  - 共有されたユーザーはDiffを編集することができる。
  - Diffを閲覧、編集可能なユーザーのことをメンバーと呼ぶ
  - メンバーはDiffをメンバーの除外をすることができる。
    - 除外するときはパスワード入力を求めること。
    - またメンバーはDiffの作成者を除外することができる。
  - Diffは閲覧モードと編集モードを用意し、編集モードの時は他のユーザーから編集できないようにすること。
   


## 使用言語
- PHP
- JavaScript(on Node.js)
- HTML
- CSS

## 使用フレームワーク
- Laravel
- Vue.js
- Bootstrap


## 開発時にホストPCに必要な環境やツール
- Docker
- Node.js
- Postman
- UTF-8対応のなんらかのIDE、エディター
- PHP
- Composer(Dockerでも可能？)
- Git

## 実行時に必要
- Docker
  - LAMP(LEMP)
  - Redis
  - Node.js
    - laravel-echo-server




## サーバーなど
docker使って制作する予定

## 前提知識
- `docker-compose up`使い方
- LaravelのControllerとModelとMigration




