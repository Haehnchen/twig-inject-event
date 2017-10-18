[POC] Twig include / extends events

```php
new IncludeNodeVisitor([
	new IncludeInjector('default/index.html.twig', 'foo2', 'before.html.twig', IncludeInjector::POSITION_PREPEND),
	new IncludeInjector('default/index.html.twig', 'foo2', 'after.html.twig', IncludeInjector::POSITION_APPEND),
	new IncludeInjector('default/index.html.twig', 'foo', 'after.html.twig', IncludeInjector::POSITION_APPEND),
]),
```
