from django.db import models

class Log(models.Model):
    action = models.TextField()
    reference_id = models.CharField(max_length=255)
    reference_name = models.CharField(max_length=255)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        db_table = 'log'

    def __str__(self):
        return f"[{self.reference_id}] {self.action[:50]}..."