{if $addons.replain.active && $addons.replain.id}
<script type="text/javascript">
    var __REPLAIN_ = '{$addons.replain.id}';
</script>

{literal}
<script type="text/javascript">
    (function(u){var s=document.createElement('script');s.type='text/javascript';s.async=true;s.src=u;
    var x=document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);
    })('https://widget.replain.cc/dist/client.js');
</script>
{/literal}
{/if}