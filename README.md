# Genel Bilgilendirme

### Server

Docker üzerinde sanallaştırılmış konteynerlar üzerinden yayınlanmıştır.

### API

Uygulamaya gitmek için [buraya tıklayın](https://yaska-group-interview-task-api.ahmetkaradeniz.dev).

Laravel 10 kullanılarak geliştirilen bir REST API olduğunu ve veri depolama için MySQL veritabanının kullanılmıştır. Geliştirme sürecinde migration yapıları ve seed yapıları kullanılmıştır, yani veritabanı şemasının oluşturulması ve test verilerinin eklenmesi gibi işlemler programatik olarak yapılmıştır.

<hr>

### ADMIN

Uygulamaya gitmek için [buraya tıklayın](https://yaska-group-interview-task-admin.ahmetkaradeniz.dev).

#### Giriş Bilgileri

Kullanıcı Adı : admin
Şifre : admin123

Vue.js 3 Composition API ve TypeScript kullanılarak oluşturulmuştur. Kullanıcı arayüzü tasarımı için Vuetify UI kitinden yararlanılmıştır.

<hr>

### WEB

Uygulamaya gitmek için [buraya tıklayın](https://yaska-group-interview-task.ahmetkaradeniz.dev).

Nuxt.js 3 Composition API ve TypeScript kullanılarak oluşturulmuştur. Kullanıcı arayüzü tasarımı için Tailwind CSS kitinden yararlanılmıştır.

<hr>

# Proje Adı

Basit Bir Blog Uygulaması

### Proje Açıklaması

Bu proje, basit bir blog uygulaması geliştirmenizi gerektirir. Kullanıcıların kaydolabileceği, giriş yapabileceği ve blog yazıları oluşturabileceği bir web sitesi oluşturmanız gerekiyor. Ayrıca, kullanıcıların diğer kullanıcıların blog yazılarını beğenip yorum yapabileceği bir yorum sistemi de eklemeniz gerekiyor.

#### Gereksinimler

- Kullanıcıların kaydolabilmesi ve giriş yapabilmesi gerekiyor. Kullanıcıların kayıt olurken ad, soyad, e-posta, kullanıcı adı ve şifre bilgilerini girmesi gerekiyor.
- Giriş yaptıklarında, blog yazılarını oluşturabilirler. Blog yazısı oluştururken, hemen yayınlanacak şekilde olmalı fakat ileri tarihli de yayınlamalı.
- Tüm kullanıcılar blog yazısı geliştirebilir.
- Her blog yazısı için başlık, içerik ve resim (isteğe bağlı) yükleyebilirler. Blog yazıları, oluşturulma tarihlerine göre sıralanmalıdır.
- Tüm blog yazılarına ve yorumlara, yalnızca kayıtlı kullanıcılar erişebilmelidir.
- Rol yönetiminde çok basit, admin ve kullanıcı şeklinde 2 farklı rol kontrolü olması gerekiyor. (Adminlerin blog yazılarını yönetebileceği bir alan için gerekli)
- Yönetici kullanıcılar, tüm blog yazılarını ve yorumları görüntüleyebilmeli ve bunları silme / düzenleme yetkisine sahip olmalıdır.
- Yönetici kullanıcılar, kullanıcıları yasaklayabilmeli ve yasaklı kullanıcı giriş yapmayı denediğinde hesabının yasaklandığıyla alakalı bir uyarı alıp login olmaması gerekiyor.

#### Proje Notları

- Projeyi yaparken aktif olarak Git kullanmanız gerekiyor.
- Laravel'in entegre edilmiş authentication ve authorization özelliklerini kullanmanız gerekiyor.
- Proje, MVC tasarım kalıbına göre yapılandırılmalıdır.
- Proje, güncel güvenlik standartlarına uygun olmalıdır.
- Projenin yönetim panelini tasarlarken, mevcut admin şablonlarından (Hazır admin şablonlarından) yararlanabilirsiniz (AdminLTE ücretsiz ve kullanımı basit temadır). CSS ve JavaScript kısmında ise, bir şablon kullanmanızda herhangi bir sakınca yoktur.
- Tercihen Front-end tarafında kullanabileceğiniz teknolojiler:
- React
- Vue

#### GENEL NOTLAR

- Uygulamayı herhangi bir platform üzerinden kodlarınızı (Git vs.) ve çalışmasını (Demo) inceleme imkanı sunacak şekilde paylaşmanız yeterlidir.
