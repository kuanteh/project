### What I Learn 
1. **swiper js 是什么？**
- 一款开源且免费的现代 JavaScript 滑动特效插件
- 内置自动播放、分页器（圆点/数字）、前进后退按钮、无限循环、懒加载及缩略图等

### 12 June 2026 Error （还没有fix）
1. 
situation : user 已经login 了 ， user 去了他的profile.php ， 但是他的 header.php 原本应该出现的 logout icon 没有在

- 怀疑是 isLoggedIn 有问题


try catch 的 catch
try - try 的里面如果会有error
catch -如果真的有error 要怎样处理
          
exception $e 
- exception 代表所有类型的error(有异常)
- $e 就是一个 variable (记录这个error 的原因)
          
db->rollBack();
- 如果其他的能add 但是只有size 不能add 
- 是没有这串代码 你照样add product 是能的,但是size 的地方因为有error 就没有显示
- 如果你有这串代码 ,他就会取消你add 因为你有一个地方是有error
- 总结:rollBack() 会撤销刚才的操作 在database
         